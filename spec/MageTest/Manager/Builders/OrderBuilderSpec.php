<?php

namespace spec\MageTest\Manager\Builders;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OrderBuilderSpec extends ObjectBehavior
{
    function let(\Mage_Customer_Model_Customer $customer,
                 \Mage_Customer_Model_Address $address,
                 \Mage_Catalog_Model_Product $product)
    {
        \Mage::app();
        $this->beConstructedWith($customer, $address, $product);
    }

    function it_should_implement_builder_interface()
    {
        $this->shouldImplement('\MageTest\Manager\Builders\BuilderInterface');
    }

    function it_should_setup_a_product_model_factory()
    {
        $this->defaultModelFactory()->shouldReturnAnInstanceOf('\Mage_Sales_Model_Quote');
    }
}
