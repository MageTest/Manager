<?php

namespace MageTest\Manager\Builders;

/**
 * Class Configurable
 * @package MageTest\Manager\Builders
 */
class Configurable extends AbstractBuilder implements BuilderInterface
{
    /**
     * @return \Mage_Catalog_Model_Product
     */
    public function build()
    {

        $configurableAttributes = $this->attributes['configurable_attributes'];

        $configurableProductsData = array();
        $attributeIds = array();

        foreach($configurableAttributes as $sku => $attributes)
        {
            $simpleProduct = \Mage::getModel('catalog/product');
            $simpleProduct->addData($this->attributes['simple_product_attributes']);
            $simpleProduct->setSku($sku);

            if(isset($attributes['price'])){
                $simpleProduct->setPrice($attributes['price']);
                unset($attributes['price']);
            }

            $childAttributes = array();

            foreach($attributes as $attributeCode => $value)
            {
                if(!isset($attributeDetails[$attributeCode])){
                    $attributeDetails[$attributeCode] = $this->getAttributeDetails($attributeCode);
                    $attributeIds[] = $attributeDetails[$attributeCode]['id'];
                }

                $valueIndex = $attributeDetails[$attributeCode]['options'][$value];

                $childAttributes[] = array(
                    'attribute_id' => $attributeDetails[$attributeCode]['id'],
                    'label' => $value,
                    'value_index' => $valueIndex,
                    'pricing_value' => $simpleProduct->getPrice()
                );

                $simpleProduct->setData($attributeCode, $valueIndex);
            }

            $simpleProduct->save();

            $configurableProductsData[$simpleProduct->getId()] = $childAttributes;
        }

        $this->model->addData($this->attributes['default_attributes']);

        $this->model->getTypeInstance()->setUsedProductAttributeIds($attributeIds);
        $configurableAttributesData = $this->model->getTypeInstance()->getConfigurableAttributesAsArray();

        $this->model->setCanSaveConfigurableAttributes(true);
        $this->model->setConfigurableAttributesData($configurableAttributesData);

        $this->model->setConfigurableProductsData($configurableProductsData);

        return $this->model;
    }

    private function getAttributeDetails($code)
    {
        $config    = \Mage::getSingleton('eav/config');
        $attribute = $config->getAttribute(\Mage_Catalog_Model_Product::ENTITY, $code);
        $options   = $attribute->getSource()->getAllOptions();

        $values = array();

        foreach($options as $option){
            $values[$option['label']] = $option['value'];
        }

        return array(
            "id" => $attribute->getId(),
            "options" => $values
        );
    }
}
