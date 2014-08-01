<?php
namespace MageTest\Manager;

class OrderTest extends WebTestCase
{
    private $orderFixture;

    protected function setUp()
    {
        parent::setUp();
        $fixture = getcwd() . '/src/MageTest/Manager/Fixtures/Order.yml';
        $this->orderFixture = $this->manager->loadFixture($fixture);
    }

    public function testCreateOrderWithOneProduct()
    {
        $this->adminLogin();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/admin/sales_order/index');
        $this->assertSession()->pageTextContains($this->orderFixture->getIncrementId());
    }

    public function testDeleteOrderWithOneProduct()
    {
        $this->manager->clear();

        $this->adminLogin();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/admin/sales_order/index');
        $this->assertSession()->pageTextNotContains($this->orderFixture->getIncrementId());
    }
}