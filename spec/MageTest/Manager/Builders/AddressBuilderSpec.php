<?php

namespace spec\MageTest\Manager\Builders;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddressBuilderSpec extends ObjectBehavior
{
    function let()
    {
        \Mage::app();
    }

    function it_should_implement_builder_interface()
    {
        $this->shouldImplement('\MageTest\Manager\Builders\BuilderInterface');
    }

    function it_should_setup_a_address_model_factory()
    {
        $this->defaultModelFactory()->shouldReturnAnInstanceOf('\Mage_Customer_Model_Address');
    }

    function it_should_build_address_with_required_attributes()
    {
        $model = $this->build();
        $model->getData()->shouldReturn(array(
            'firstname' => 'test name',
            'lastname' => 'test name',
            'customer_id' => '1',
            'parent_id' => '1',
            'company'   => 'Session Digital',
            'street' => 'Brown Street',
            'street1' => 'Brown Street',
            'city' => 'Manchester',
            'postcode' =>  'M2 2JG',
            'region' => 'Lancashire',
            'country' => 'United Kingdom',
            'country_id' => 'GB',
            'telephone' => '1234567890',
            'is_default_billing' => 1,
            'is_default_shipping' => 1,
            'save_in_address_book' => '1',
            'use_for_shipping' => '1'
        ));
    }
}
