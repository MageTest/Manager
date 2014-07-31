<?php
namespace MageTest\Manager\Builders;

use Mage;

abstract class AbstractBuilder
{
    public $attributes;
    public $model;

    public function __construct($modelType)
    {
        $this->attributes = array();
        $this->model = Mage::getModel($modelType);
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function getWebsiteIds()
    {
        $ids = array();
        foreach (Mage::getModel('core/website')->getCollection() as $website) {
            $ids[] = $website->getId();
        }
        return $ids;
    }
} 