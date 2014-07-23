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
            'customer' => new CustomerBuilder(),
            'product'  => new ProductBuilder()
        );
    }

    public function testCreateOrderWithOneProduct()
    {
        $customer = $this->manager->create('customer', $this->builders['customer']);
        $address = $this->manager->create('address', new AddressBuilder($customer));
        $product = $this->manager->create('product', $this->builders['product']);

        $orderBuilder = new OrderBuilder($customer, $address);
        $order = $this->manager->create('order', $orderBuilder->withSimpleProduct($product));

        $this->adminLogin();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/admin/sales_order/index');
        $this->assertSession()->pageTextContains($order->getIncrementId());
    }

    public function testDeleteOrderWithOneProduct()
    {
        $customer = $this->manager->create('customer', $this->builders['customer']);
        $address = $this->manager->create('address', new AddressBuilder($customer));
        $product = $this->manager->create('product', $this->builders['product']);

        $orderBuilder = new OrderBuilder($customer, $address);
        $order = $this->manager->create('order', $orderBuilder->withSimpleProduct($product));

        $incrementId = $order->getIncrementId();

        $this->manager->clear();

        $this->adminLogin();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/admin/sales_order/index');
        $this->assertSession()->pageTextNotContains($incrementId);
    }
}