<?php
namespace MageTest\Manager;

class ConfigurableProductTest extends WebTestCase
{
    private $configurableProductFixture;

    protected function setUp()
    {
        parent::setUp();
        $this->configurableProductFixture = $this->manager->loadFixture('catalog/product/configurable');
    }

    public function testCreateConfigurableProduct()
    {
        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $this->configurableProductFixture->getId());

        $this->assertSession()->statusCodeEquals(200);
    }
}