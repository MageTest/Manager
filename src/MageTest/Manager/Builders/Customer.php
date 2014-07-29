<?php

namespace MageTest\Manager\Builders;

use MageTest\Manager\Builders\BuilderInterface;
use Mage;

class Customer extends AbstractBuilder implements BuilderInterface
{
    public function build()
    {
        $model = Mage::getModel($this->modelType)->addData($this->attributes);
        $model->save();
        $model->setConfirmation(null);
        return $model;
    }
}