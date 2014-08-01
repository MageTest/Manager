<?php

namespace MageTest\Manager\Builders;

/**
 * Class Customer
 * @package MageTest\Manager\Builders
 */
class Customer extends AbstractBuilder implements BuilderInterface
{
    /**
     * @return \Mage_Customer_Model_Customer
     */
    public function build()
    {
        $this->model->addData($this->attributes);
        $this->model->save();
        $this->model->setConfirmation(null);
        return $this->model;
    }
}