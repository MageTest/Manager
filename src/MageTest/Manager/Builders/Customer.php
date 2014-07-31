<?php

namespace MageTest\Manager\Builders;

use MageTest\Manager\Builders\BuilderInterface;
use Mage;

class Customer extends AbstractBuilder implements BuilderInterface
{
    public function build()
    {
        $this->model->addData($this->attributes);
        $this->model->save();
        $this->model->setConfirmation(null);
        return $this->model;
    }
}