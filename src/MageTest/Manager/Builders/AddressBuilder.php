<?php

namespace MageTest\Manager\Builders;

use Mage;

class AddressBuilder implements BuilderInterface
{

    private $attributes;
    private $model;
    private $customer;

    public function __construct(\Mage_Customer_Model_Customer $customer)
    {
        $this->model = $this->defaultModelFactory();
        $this->customer = $customer;
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

    /**
     * Build fixture model
     */
    public function build()
    {
        $this->model->setCustomer($this->customer);
        $this->attributes['firstname'] = $this->customer->getFirstname();
        $this->attributes['lastname'] = $this->customer->getLastname();
        return $this->model->addData($this->attributes);
    }
}
