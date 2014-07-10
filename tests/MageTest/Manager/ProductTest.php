<?php
namespace MageTest\Manager;

use Mage;

class ProductTest extends WebTestCase
{
    private $fixtures;
    private $product;

    protected function setUp()
    {
        parent::setUp();
        $this->fixtures = new Product();
    }

    protected function tearDown()
    {
        if ($this->product) {
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
            $this->fixtures->delete();
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);
        }
    }

    public function testCreateSimpleProduct()
    {

        $this->product = $this->fixtures->create($this->getProductAttributes());

        $attributes = $this->getProductAttributes();
        $entityId = Mage::getModel('catalog/product')->getIdBySku($attributes['sku']);

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $entityId);
        $this->assertSession()->statusCodeEquals(200);
    }

    public function testDeleteSimpleProdct()
    {
        $this->product = $this->fixtures->create($this->getProductAttributes());

        $attributes = $this->getProductAttributes();
        $entityId = Mage::getModel('catalog/product')->getIdBySku($attributes['sku']);

        $this->fixtures->delete();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $entityId);
        $this->assertSession()->statusCodeEquals(404);
    }

    private function getProductAttributes()
    {
        return array(
            'sku'               => 'test-product-123',
            'name'              => 'test product'
        );
    }
}
 