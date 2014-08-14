<?php

namespace MageTest\Manager\Builders;

use Mage;

/**
 * Class Order
 * @package MageTest\Manager\Builders
 */
class Order extends AbstractBuilder implements BuilderInterface
{
    /**
     * @param \Mage_Catalog_Model_Product $product
     * @param int $qty
     * @return $this
     */
    public function withProduct($product, $qty = 1)
    {
        $newProd = Mage::getModel('catalog/product');
        $newProd->load($newProd->getIdBySku($product->getSku()));

        $this->model->addProduct($newProd, new \Varien_Object(array(
            'qty' => $qty
        )));
        return $this;
    }

    /**
     * @param \Mage_Customer_Model_Customer $customer
     * @return $this
     */
    public function withCustomer($customer)
    {
        $this->model->assignCustomer($customer);
        return $this;
    }

    /**
     * @param \Mage_Customer_Model_Address $address
     * @return $this
     */
    public function withAddress($address)
    {
        $this->model->getBillingAddress()->addData($address->getData());
        $this->model->getShippingAddress()->addData($address->getData())
            ->setCollectShippingRates(true)->collectShippingRates()
            ->setShippingMethod($this->attributes['shipping_method'])
            ->setPaymentMethod($this->attributes['payment_method']);

        return $this;
    }

    /**
     * Build fixture model
     */
    public function build()
    {
        $this->model->setStoreId($this->model->getStoreId());

        $this->model->getPayment()->importData(array('method' => $this->attributes['payment_method']));

        $this->model->collectTotals()->save();

        \Mage::app()->getStore()->setConfig(\Mage_Sales_Model_Order::XML_PATH_EMAIL_ENABLED, '0');

        $service = Mage::getModel('sales/service_quote', $this->model);
        $service->submitAll();
        return $service->getOrder();
    }
}