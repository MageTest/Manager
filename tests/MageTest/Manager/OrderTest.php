<?php
namespace MageTest\Manager;

use Mage;

class OrderTest extends WebTestCase
{
    private $fixtures;
    private $address;
    private $product;
    private $customer;

    private $customerId;
    private $productId;
    private $orderId;
    private $addressId;
    private $order;

    protected function setUp()
    {
        parent::setUp();
        $this->fixtures = array(
            'address'  => new Address(),
            'customer' => new Customer(),
            'product'  => new Product(),
            'order'    => new Order()
        );
    }

    protected function tearDown()
    {
        Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
        foreach($this->fixtures as $fixture){
            $fixture->delete();
        }
        Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);
        parent::tearDown();
    }

    public function testCreateOrderWithOneProduct()
    {
        $email = 'test@example.com';
        $pass = 'qwerty123';

        $this->customerId = $this->fixtures['customer']->create($this->getCustomerAttributes($email, $pass));
        $this->customer = Mage::getModel('customer/customer')->load($this->customerId);

        $this->fixtures['address']->setCustomer($this->customer);

        $testAddress = $this->getAddressAttributes($this->customer);
        $this->addressId = $this->fixtures['address']->create($testAddress);
        $this->address = Mage::getModel('customer/address')->load($this->addressId);

        $this->product = $this->fixtures['product']->create($this->getProductAttributes());
        $this->productId = $this->product->getProductId();

        $this->orderId   = $this->fixtures['order']->create($this->getOrderAttributes());
        $this->order = Mage::getModel('sales/order')->load($this->orderId);

        $this->adminLogin();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/admin/sales_order/index');
        $this->assertSession()->pageTextContains($this->order->getIncrementId());
    }

    private function getOrderAttributes()
    {
        return array(
            'customer' =>  $this->customer,
            'items' => array(
                array(
                    'product' => Mage::getModel('catalog/product')->load($this->productId),
                    'qty' => 1
                   ),
            ),
           'billingAddress' => $this->address,
           'shippingAddress' => $this->address,
           'paymentMethod' => 'checkmo',
           'shippingMethod' => 'flatrate_flatrate'
       );
    }
}