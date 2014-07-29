<?php

namespace MageTest\Manager\Builders;

use MageTest\Manager\Builders\BuilderInterface;
use Mage;

class Customer implements BuilderInterface
{
    private $attributes;
    private $modelType;

    public function build()
    {
        $model = Mage::getModel($this->modelType)->addData($this->attributes);
        $model->save();
        $model->setConfirmation(null);
        return $model;
    }

    public function setModelType($modelType)
    {
        $this->modelType = $modelType;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
}