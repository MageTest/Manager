<?php

namespace MageTest\Manager\Builders;

use Mage;

class Address extends AbstractBuilder implements BuilderInterface
{
    public function withCustomer(\Mage_Customer_Model_Customer $customer)
    {
        $this->attributes['customer_id'] = $customer->getId();
        $this->attributes['firstname'] = $customer->getFirstname();
        $this->attributes['lastname'] = $customer->getLastname();
        return $this;
    }

    /**
     * Build fixture model
     */
    public function build()
    {
        $this->model->setCustomerId($this->attributes['customer_id']);
        return $this->model->addData($this->attributes);
    }
}