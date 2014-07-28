<?php

namespace MageTest\Manager;

use MageTest\Manager\Builders\BuilderInterface;
use Mage;

class Builder implements BuilderInterface
{
    private $attributes;
    private $modelType;

    public function __construct($attributes, $modelType)
    {
        $this->attributes = $attributes;
        $this->modelType = $modelType;
    }

    public function build()
    {
        return Mage::getModel($this->modelType)->addData($this->attributes);
    }
}