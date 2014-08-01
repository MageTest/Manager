<?php
namespace MageTest\Manager\Builders;

use Mage;

/**
 * Class AbstractBuilder
 * @package MageTest\Manager\Builders
 */
abstract class AbstractBuilder
{
    /**
     * @var array
     */
    public $attributes;
    /**
     * @var false|\Mage_Core_Model_Abstract
     */
    public $model;

    /**
     * @param $modelType
     */
    public function __construct($modelType)
    {
        $this->attributes = array();
        $this->model = Mage::getModel($modelType);
    }

    /**
     * @param $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getWebsiteIds()
    {
        $ids = array();
        foreach (Mage::getModel('core/website')->getCollection() as $website) {
            $ids[] = $website->getId();
        }
        return $ids;
    }
} 