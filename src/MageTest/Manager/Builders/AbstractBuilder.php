<?php
namespace MageTest\Manager\Builders;


abstract class AbstractBuilder
{
    public $attributes;
    public $modelType;

    public function __construct()
    {
        $this->attributes = array();
        $this->modelType = array();
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