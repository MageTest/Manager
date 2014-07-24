<?php
namespace MageTest\Manager;

use Mage;
use MageTest\Manager\Builders\AddressBuilder;
use MageTest\Manager\Builders\CustomerBuilder;
use MageTest\Manager\Builders\OrderBuilder;
use MageTest\Manager\Builders\ProductBuilder;

class OrderTest extends WebTestCase
{
    private $builders;

    protected function setUp()
    {
        parent::setUp();
        $this->builders = array(
            'address'  => new AddressBuilder(),
            'customer' => new CustomerBuilder(),
            'order' => new OrderBuilder(),
            'product'  => new ProductBuilder()
        );
    }

    public function testCreateOrderWithOneProduct()
    {
        $order = $this->orderWithOneProduct();

        $this->adminLogin();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/admin/sales_order/index');
        $this->assertSession()->pageTextContains($order->getIncrementId());
    }

    public function testDeleteOrderWithOneProduct()
    {
        $order = $this->orderWithOneProduct();

        $incrementId = $order->getIncrementId();

        $this->manager->clear();

        $this->adminLogin();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/admin/sales_order/index');
        $this->assertSession()->pageTextNotContains($incrementId);
    }

    /**
     * @return mixed
     */
    private function orderWithOneProduct()
    {
        $customer = $this->manager->create('customer', $this->builders['customer']);
        $address = $this->manager->create('address', $this->builders['address']->withCustomer($customer));
        $product = $this->manager->create('product', $this->builders['product']);
        $order = $this->manager->create('order', $this->builders['order']->withCustomer($customer)
            ->withAddress($address)
            ->withSimpleProduct($product));
        return $order;
    }
}