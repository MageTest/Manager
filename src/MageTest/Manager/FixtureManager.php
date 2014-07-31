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
        $this->attributesProvider->readFile($fixtureFile);

        $builder = $this->getBuilder($this->attributesProvider->getModelType());
        $builder->setAttributes($this->attributesProvider->readAttributes());
        $builder->setModelType($this->attributesProvider->getModelType());

        if($this->attributesProvider->hasFixtureDependencies())
        {
            foreach($this->attributesProvider->getFixtureDependencies() as $dependency)
            {

                $withDependency = 'with' . $this->getFixtureTemplate($dependency);
                $builder->$withDependency($this->buildFixtureDependency($dependency));
            }
        }

        $this->create($this->attributesProvider->getModelType(), $builder);
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
            case 'customer/address': return $this->builders[$modelType] = new Builders\Address();
            case 'customer/customer': return $this->builders[$modelType] = new Builders\Customer();
            case 'catalog/product': return $this->builders[$modelType] = new Builders\Product();
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

    private function buildFixtureDependency($dependency)
    {
        $dependencyBuilder = $this->getBuilder($dependency);
        $dependencyBuilder->setAttributes($this->getDependencyAttributes($dependency));
        $dependencyBuilder->setModelType($dependency);
        return $this->create($dependency, $dependencyBuilder);
    }

    private function getDependencyAttributes($dependency)
    {
       $dependencyAttributes = clone $this->attributesProvider;
       $dependencyAttributes->readFile(
           getcwd() . '/src/MageTest/Manager/Fixtures/'
           . $this->getFixtureTemplate($dependency)
           . $this->attributesProvider->getFileType()
       );
       return $dependencyAttributes->readAttributes();
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