<?php

namespace spec\MageTest\Manager\Builders;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddressBuilderSpec extends ObjectBehavior
{
    function let(\Mage_Customer_Model_Customer $customer)
    {
        \Mage::app();
        $this->beConstructedWith($customer);
    }

    function it_should_implement_builder_interface()
    {
        $this->shouldImplement('\MageTest\Manager\Builders\BuilderInterface');
    }

    function it_should_setup_a_address_model_factory()
    {
        $this->defaultModelFactory()->shouldReturnAnInstanceOf('\Mage_Customer_Model_Address');
    }}
