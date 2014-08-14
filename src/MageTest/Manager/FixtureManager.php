<?php
namespace MageTest\Manager;

use MageTest\Manager\Attributes\Provider\ProviderInterface;
use MageTest\Manager\Builders\BuilderInterface;
use MageTest\Manager\Builders;

/**
 * Class FixtureManager
 * @package MageTest\Manager
 */
class FixtureManager
{
    /**
     * @var array
     */
    private $fixtures;

    /**
     * @var array
     */
    private $builders;

    /* @var \MageTest\Manager\Attributes\Provider\ProviderInterface */
    private $attributesProvider;

    /**
     * @param ProviderInterface $attributesProvider
     */
    public function __construct(ProviderInterface $attributesProvider)
    {
        $this->fixtures = array();
        $this->builders = array();
        $this->attributesProvider = $attributesProvider;
    }

    /**
     * @param $fixtureFile
     * @return mixed
     */
    public function loadFixture($fixtureType, $userFixtureFile = null)
    {
        $attributesProvider = clone $this->attributesProvider;

        if(!is_null($userFixtureFile))
        {
            $this->fixtureFileExists($userFixtureFile);
            $attributesProvider->readFile($userFixtureFile);
        } else {
            $fixtureFile = $this->getDefaultFixtureTemplate($fixtureType);
            $this->fixtureFileExists($fixtureFile);
            $attributesProvider->readFile($fixtureFile);
        }

        $attributes = $attributesProvider->readAttributes();

        $builder = $this->getBuilder($attributesProvider->getModelType());

        $builder->setAttributes($attributes);

        if($attributesProvider->hasFixtureDependencies())
        {
            foreach($attributesProvider->getFixtureDependencies() as $dependency)
            {
                $withDependency = 'with' . $this->getFixtureTemplate($dependency);
                $builder->$withDependency($this->loadFixture($dependency));
            }
        }

        return $this->create($builder);
    }

    /**
     * @param $name
     * @param BuilderInterface $builder
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function create(BuilderInterface $builder)
    {
        $model = $builder->build();

        $savedCurrentStoreId = \Mage::app()->getStore()->getId();
        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
        $model->save();
        \Mage::app()->setCurrentStore($savedCurrentStoreId);

        if($model->getTypeId() === \Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE)
        {
            $childProducts = \Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $model);
            foreach($childProducts as $product)
            {
                $this->fixtures[] = $product;
            }
        }

        return $this->fixtures[] = $model;
    }

    /**
     * Deletes all the magento fixtures
     */
    public function clear()
    {
        foreach ($this->fixtures as $fixture) {
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
            $fixture->delete();
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);
        }
        $this->fixtures = array();
    }

    /**
     * @param $modelType
     * @return Builders\Address|Builders\Customer|Builders\Order|Builders\Product
     */
    private function getBuilder($modelType)
    {
        switch($modelType)
        {
            case 'customer/address': return new Builders\Address($modelType);
            case 'customer/customer': return new Builders\Customer($modelType);
            case 'catalog/product/simple': return new Builders\Product('catalog/product');
            case 'catalog/product/configurable': return new Builders\Configurable('catalog/product');
            case 'sales/quote': return new Builders\Order($modelType);
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
     * @return string
     */
    private function getFixtureTemplate($dependency)
    {
        $fixtureTemplate = explode('/', $dependency);
        return ucfirst($fixtureTemplate[1]);
    }

    /**
     * @param $fixtureType
     * @return string
     */
    private function getDefaultFixtureTemplate($fixtureType)
    {
        $filePath = __DIR__ . '/Fixtures/';
        switch($fixtureType)
        {
            case 'customer/address': return $filePath . 'Address.yml';
            case 'customer/customer': return $filePath . 'Customer.yml';
            case 'catalog/product/simple' : return $filePath . 'Product.yml';
            case 'catalog/product/configurable' : return $filePath . 'Configurable.yml';
            case 'sales/quote': return $filePath . 'Order.yml';
        }
    }
}
