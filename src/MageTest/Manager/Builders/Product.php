<?php

namespace MageTest\Manager\Builders;

use Mage;

class Product extends AbstractBuilder implements BuilderInterface
{
    public function withImage($imageURL)
    {
        if (!$imageURL || !file_exists($imageURL)) {
            Mage::throwException(Mage::helper('catalog')->__('Image does not exist.'));
        }

        $this->model->setMediaGallery (array('images'=>array (), 'values'=>array ()))
            ->addImageToMediaGallery($imageURL, array('image','thumbnail','small_image'), false, false);

        return $this;
    }

    public function build()
    {
        return Mage::getModel($this->modelType)->addData($this->attributes);
    }

    private function getWebsiteIds()
    {
        $ids = array();
        foreach (Mage::getModel('core/website')->getCollection() as $website) {
            $ids[] = $website->getId();
        }

        return $ids;
    }

    private function retrieveDefaultAttributeSetId()
    {
        return Mage::getModel('catalog/product')
            ->getResource()
            ->getEntityType()
            ->getDefaultAttributeSetId();
    }
}
