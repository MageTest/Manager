<?php
namespace MageTest\Manager;

use Guzzle\Http\Client;
Use MageTest\Manager\Builders\ProductBuilder;
use Mage;

class ProductTest extends WebTestCase
{
    private $builder;
    private $product;

    protected function setUp()
    {
        parent::setUp();
        $this->builder = new ProductBuilder();
    }

    public function testCreateSimpleProduct()
    {
        $this->product = $this->manager->create('product', $this->builder);

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $this->product->getId());
        $this->assertSession()->statusCodeEquals(200);
    }

    public function testDeleteSimpleProduct()
    {
        $this->product = $this->manager->create('product', $this->builder);

        $this->manager->clear();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $this->product->getId());
        $this->assertSession()->statusCodeEquals(404);
    }

    public function testCreateSimpleProductWithImage()
    {
        $imageURL = getcwd() . '/tests/MageTest/Manager/Assets/370x370.jpg';
        $this->product = $this->manager->create('product', $this->builder->withImage($imageURL));

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $this->product->getId());
        $this->assertSession()->elementExists('css', '#image');
    }
}
 