<?php

namespace MageTest\Manager\Builders;

use Mage;

class Product extends AbstractBuilder implements BuilderInterface
{
    public function build()
    {
        return $this->model->addData($this->attributes);
    }

    private function retrieveDefaultAttributeSetId()
    {
        return Mage::getModel($this->modelType)
            ->getResource()
            ->getEntityType()
            ->getDefaultAttributeSetId();
    }
}
