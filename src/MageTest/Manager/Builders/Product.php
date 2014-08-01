<?php

namespace MageTest\Manager\Builders;

/**
 * Class Product
 * @package MageTest\Manager\Builders
 */
class Product extends AbstractBuilder implements BuilderInterface
{
    /**
     * @return \Mage_Catalog_Model_Product
     */
    public function build()
    {
        return $this->model->addData($this->attributes);
    }
}
