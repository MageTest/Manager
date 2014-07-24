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

    public function __construct()
    {
        $this->model = $this->defaultModelFactory();
    }

    public function withSimpleProduct(\Mage_Catalog_Model_Product $product, $qty = 1)
    {
        $this->model->addProduct($product, new \Varien_Object(array(
            'qty' => $qty
        )));
        return $this;
    }

    public function withCustomer(\Mage_Customer_Model_Customer $customer)
    {
        $this->model->assignCustomer($customer);
        return $this;
    }

    public function withAddress(\Mage_Customer_Model_Address $address)
    {
        $this->model->getBillingAddress()->addData($address->getData());
        $this->model->getShippingAddress()->addData($address->getData())
            ->setCollectShippingRates(true)->collectShippingRates()
            ->setShippingMethod('flatrate_flatrate')
            ->setPaymentMethod('checkmo');

        return $this;
    }

    /**
     * Build fixture model
     */
    public function build()
    {
        $this->model->setStoreId($this->model->getStoreId());

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