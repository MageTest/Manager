<?php

namespace MageTest\Manager\Builders;

use Mage;

class AddressBuilder implements BuilderInterface
{

    private $attributes;
    private $model;

    public function __construct()
    {
        $this->model = $this->defaultModelFactory();
        $this->attributes = array(
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
        );
    }

    public function defaultModelFactory()
    {
        return Mage::getModel('customer/address');
    }

    public function withCustomer(\Mage_Customer_Model_Customer $customer)
    {
        $this->model->setCustomer($customer);
        $this->attributes['firstname'] = $customer->getFirstname();
        $this->attributes['lastname'] = $customer->getLastname();
        return $this;
    }

    /**
     * Build fixture model
     */
    public function build()
    {
        return $this->model->addData($this->attributes);
    }
}