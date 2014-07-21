<?php

namespace spec\MageTest\Manager\Builders;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CustomerBuilderSpec extends ObjectBehavior
{
    function let()
    {
        \Mage::app();
    }

    function it_should_implement_builder_interface()
    {
        $this->shouldImplement('\MageTest\Manager\Builders\BuilderInterface');
    }

    function it_should_setup_a_customer_model_factory()
    {
        $this->defaultModelFactory()->shouldReturnAnInstanceOf('\Mage_Customer_Model_Customer');
    }

    function it_should_build_customer_with_required_attributes()
    {
        $model = $this->build();
        $model->getData()->shouldReturn(array(
            "website_id" => \Mage::app()->getWebsite()->getId(),
            "store" => \Mage::app()->getStore(),
            "email" => 'customer@example.com',
            "firstname" => 'test',
            "lastname" => 'test',
            "password" => '123123pass',
            "confirmation" => '123123pass',
            "status" => 1
        ));
    }
}
