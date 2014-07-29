<?php
/**
 * Created by PhpStorm.
 * User: jporter
 * Date: 7/18/14
 * Time: 5:06 PM
 */

namespace MageTest\Manager;

use MageTest\Manager\Attributes\Provider\YamlProvider;
use MageTest\Manager\Builders\BuilderInterface;
use MageTest\Manager\Builders;

class FixtureManager
{
    private $fixtures;
    private $builders;
    private $attributesProvider;

    public function __construct()
    {
        $this->fixtures = array();
        $this->builders = array();
        $this->attributesProvider = array();
    }

    public function loadFixture($fixtureFile)
    {
        $this->fixtureFileExists($fixtureFile);

        $attributesProvider = $this->getAttributesProvider($fixtureFile);

        $attributesProvider->setFile($fixtureFile);

        $builder = $this->getBuilder($attributesProvider->getModelType());
        $builder->setAttributes($attributesProvider->readAttributes());
        $builder->setModelType($attributesProvider->getModelType());

        $this->create($attributesProvider->getModelType(), $builder);
    }

    public function create($name, BuilderInterface $builder)
    {
        if($this->hasFixture($name))
        {
            throw new \InvalidArgumentException("Fixture $name has already been set. Please use unique names.");
        }

        $model = $builder->build();

        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
        $model->save();
        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);

        return $this->fixtures[$name] = $model;
    }

    public function getFixture($name)
    {
        if(!$this->hasFixture($name))
        {
            throw new \InvalidArgumentException("Could not find a fixture: $name");
        }
        return $this->fixtures[$name];
    }

    private function hasFixture($name) {
        return array_key_exists($name, $this->fixtures);
    }

    public function clear()
    {
        foreach ($this->fixtures as $fixture) {
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
            $fixture->delete();
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);
        }
        $this->fixtures = array();
    }

    private function getAttributesProvider($file)
    {
        $fileType = pathinfo($file, PATHINFO_EXTENSION);
        switch($fileType){
            case 'yml':
                return $this->attributesProvider[$fileType] = new YamlProvider();
        }
    }

    private function hasBuilder($name) {
        return array_key_exists($name, $this->builders);
    }

    private function getBuilder($modelType)
    {
        if($this->hasBuilder($modelType))
        {
            return $this->builders[$modelType];
        }

        switch($modelType)
        {
            case 'customer/customer': return new Builders\Customer();
            case 'catalog/product': return new Builders\Product();
        }
    }

    /**
     * @param $fixtureFile
     * @throws \InvalidArgumentException
     */
    private function fixtureFileExists($fixtureFile)
    {
        if (!file_exists($fixtureFile)) {
            throw new \InvalidArgumentException("The fixture file: $fixtureFile does not exist. Please check path.");
        }
    }
}