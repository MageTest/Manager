<?php
/**
 * Created by PhpStorm.
 * User: jporter
 * Date: 7/22/14
 * Time: 5:22 PM
 */

namespace MageTest\Manager\Builders;


use Mage;

class OrderBuilder implements BuilderInterface
{
    private $model;
    private $customer;
    private $address;

    public function __construct($customer, $address)
    {
        $this->model = $this->defaultModelFactory();
        $this->customer = $customer;
        $this->address = $address;
    }

    public function withSimpleProduct(\Mage_Catalog_Model_Product $product, $qty = 1)
    {
        $this->model->addProduct($product, new \Varien_Object(array(
            'qty' => $qty
        )));
        return $this;
    }

    /**
     * Build fixture model
     */
    public function build()
    {
        $this->model->setStoreId(Mage::app()->getStore('default')->getId());

        $this->model->assignCustomer($this->customer);

        $billingAddress = $this->model->getBillingAddress()->addData($this->address->getData());
        $shippingAddress = $this->model->getShippingAddress()->addData($this->address->getData());

        $shippingAddress->setCollectShippingRates(true)->collectShippingRates()
            ->setShippingMethod('flatrate_flatrate')
            ->setPaymentMethod('checkmo');

        $this->model->getPayment()->importData(array('method' => 'checkmo'));

        $this->model->collectTotals()->save();

        \Mage::app()->getStore()->setConfig(\Mage_Sales_Model_Order::XML_PATH_EMAIL_ENABLED, '0');

        $service = Mage::getModel('sales/service_quote', $this->model);
        $service->submitAll();
        return $service->getOrder();
    }

    public function defaultModelFactory()
    {
        return Mage::getModel('sales/quote');
    }
}