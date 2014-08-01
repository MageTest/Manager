<?php

namespace MageTest\Manager\Builders;

/**
 * Class Address
 * @package MageTest\Manager\Builders
 */
class Address extends AbstractBuilder implements BuilderInterface
{
    /**
     * @param \Mage_Customer_Model_Customer $customer
     * @return $this
     */
    public function withCustomer($customer)
    {
        $this->attributes['customer_id'] = $customer->getId();
        $this->attributes['firstname'] = $customer->getFirstname();
        $this->attributes['lastname'] = $customer->getLastname();
        return $this;
    }

    /**
     * @return \Mage_Customer_Model_Address
     */
    public function build()
    {
        $this->model->setCustomerId($this->attributes['customer_id']);
        return $this->model->addData($this->attributes);
    }
}