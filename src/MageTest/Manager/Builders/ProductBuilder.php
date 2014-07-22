<?php

namespace MageTest\Manager\Builders;

use Mage;

class ProductBuilder implements BuilderInterface
{
    private $attributes = array();
    private $model;

    public function __construct()
    {
        $this->model = $this->defaultModelFactory();
        $this->attributes = array(
            'sku'               => 'test-sku-123',
            'attribute_set_id'  => $this->retrieveDefaultAttributeSetId(),
            'name'              => 'product name',
            'weight'            => 2,
            'visibility'        => \Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
            'status'            => \Mage_Catalog_Model_Product_Status::STATUS_ENABLED,
            'price'             => 100,
            'description'       => 'Product description',
            'short_description' => 'Product short description',
            'tax_class_id'      => 1,
            'type_id'           => \Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
            'stock_data'        => array( 'is_in_stock' => 1, 'qty' => 99999 ),
            'website_ids'       => $this->getWebsiteIds()
        );
    }

    public function withImage($imageURL)
    {
        if (!$imageURL || !file_exists($imageURL)) {
            Mage::throwException(Mage::helper('catalog')->__('Image does not exist.'));
        }

        $this->model->setMediaGallery (array('images'=>array (), 'values'=>array ()))
            ->addImageToMediaGallery($imageURL, array('image','thumbnail','small_image'), false, false);

        return $this;
    }

    public function build()
    {
        return $this->model->addData($this->attributes);
    }

    public function defaultModelFactory()
    {
        return \Mage::getModel('catalog/product');
    }

    private function getWebsiteIds()
    {
        $ids = array();
        foreach (Mage::getModel('core/website')->getCollection() as $website) {
            $ids[] = $website->getId();
        }

        return $ids;
    }

    private function retrieveDefaultAttributeSetId()
    {
        return Mage::getModel('catalog/product')
            ->getResource()
            ->getEntityType()
            ->getDefaultAttributeSetId();
    }
}
