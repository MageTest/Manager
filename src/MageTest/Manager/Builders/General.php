<?php

namespace MageTest\Manager\Builders;

/**
 * Class General
 * @package MageTest\Manager\Builders
 */
class General extends AbstractBuilder implements BuilderInterface
{
    /**
     * @return \Mage_Core_Model_Abstract
     */
    public function build()
    {
        return $this->model->addData($this->attributes);
    }
}
