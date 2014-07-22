<?php

namespace MageTest\Manager\Builders;

use Mage;

class CustomerBuilder implements BuilderInterface
{
    private $attributes = array();

    public function __construct()
    {
        $this->attributes = array(
            "website_id" => Mage::app()->getWebsite()->getId(),
            "store" => Mage::app()->getStore(),
            "email" => 'customer@example.com',
            "firstname" => 'test',
            "lastname" => 'test',
            "password" => '123123pass',
            "confirmation" => '123123pass',
            "status" => 1
        );
    }

    public function build()
    {
        $model = $this->defaultModelFactory();
        return $model->addData($this->attributes);
    }

    public function defaultModelFactory()
    {
        return \Mage::getModel('customer/customer');
    }
}
