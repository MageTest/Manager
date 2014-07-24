<?php

namespace spec\MageTest\Manager\Builders;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProductBuilderSpec extends ObjectBehavior
{
    function let()
    {
        \Mage::app();
    }

    function it_should_implement_builder_interface()
    {
        $this->shouldImplement('\MageTest\Manager\Builders\BuilderInterface');
    }

    function it_should_setup_a_product_model_factory()
    {
        $this->defaultModelFactory()->shouldReturnAnInstanceOf('\Mage_Catalog_Model_Product');
    }

    function it_should_build_product_with_required_attributes()
    {
        $model = $this->build();
        $model->getData()->shouldBeLike(array(
            'sku'               => 'test-sku-123',
            'attribute_set_id'  => 9,
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
            'website_ids'       => array(1)
        ));
    }

    function it_should_add_an_image_as_an_optional_process()
    {
        $imageURL= 'vendor/magetest/magento/src/skin/frontend/base/default/images/catalog/product/placeholder/image.jpg';
        $model = $this->withImage($imageURL)->build();
        $model->getData()->shouldHaveKey('image');
    }
}
