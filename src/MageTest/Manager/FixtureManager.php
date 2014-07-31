<?php
namespace MageTest\Manager;

use MageTest\Manager\Attributes\Provider\ProviderInterface;
use MageTest\Manager\Builders\BuilderInterface;
use MageTest\Manager\Builders;

class FixtureManager
{
    private $fixtures;
    private $builders;

    /* @var \MageTest\Manager\Attributes\Provider\YamlProvider */
    private $attributesProvider;

    public function __construct(ProviderInterface $attributesProvider)
    {
        $this->fixtures = array();
        $this->builders = array();
        $this->attributesProvider = $attributesProvider;
    }

    public function loadFixture($fixtureFile)
    {
        $this->fixtureFileExists($fixtureFile);
        $attributesProvider = clone $this->attributesProvider;
        $attributesProvider->readFile($fixtureFile);

        $builder = $this->getBuilder($attributesProvider->getModelType());
        $builder->setAttributes($attributesProvider->readAttributes());

        if($attributesProvider->hasFixtureDependencies())
        {
            foreach($attributesProvider->getFixtureDependencies() as $dependency)
            {
                $withDependency = 'with' . $this->getFixtureTemplate($dependency);
                $builder->$withDependency($this->loadFixture(
                    getcwd() . '/src/MageTest/Manager/Fixtures/'
                    . $this->getFixtureTemplate($dependency)
                    . $this->attributesProvider->getFileType()
                ));
            }
        }

        return $this->create($attributesProvider->getModelType(), $builder);
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

    public function clear()
    {
        foreach ($this->fixtures as $fixture) {
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
            $fixture->delete();
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);
        }
        $this->fixtures = array();
    }

    private function hasFixture($name) {
        return array_key_exists($name, $this->fixtures);
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
            case 'customer/address': return $this->builders[$modelType] = new Builders\Address($modelType);
            case 'customer/customer': return $this->builders[$modelType] = new Builders\Customer($modelType);
            case 'catalog/product': return $this->builders[$modelType] = new Builders\Product($modelType);
            case 'sales/quote': return $this->builders[$modelType] = new Builders\Order($modelType);
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

    /**
     * @param $dependency
     */
    private function getFixtureTemplate($dependency)
    {
        $fixtureTemplate = explode('/', $dependency);
        return ucfirst($fixtureTemplate[1]);
    }
}