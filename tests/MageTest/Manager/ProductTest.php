<?php
namespace MageTest\Manager;

use MageTest\Manager\Attributes\Provider\YamlProvider;

class ProductTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $fixture = getcwd() . '/fixtures/Product.yml';
        $this->manager->loadFixture($fixture);
    }

    public function testCreateSimpleProduct()
    {
        $product = $this->manager->getFixture('catalog/product');

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $product->getId());
        $this->assertSession()->statusCodeEquals(200);
    }

    public function testDeleteSimpleProduct()
    {
        $product = $this->manager->getFixture('catalog/product');

        $this->manager->clear();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $product->getId());
        $this->assertSession()->statusCodeEquals(404);
    }

    public function testCreateSimpleProductWithImage()
    {
        $product = $this->manager->getFixture('catalog/product');

        $imageURL = getcwd() . '/tests/MageTest/Manager/Assets/370x370.jpg';

        $product->setMediaGallery (array('images'=>array (), 'values'=>array ()));
        $product->addImageToMediaGallery($imageURL, array('image','thumbnail','small_image'), false, false);
        $product->save();


        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $product->getId());
        $this->assertSession()->elementExists('css', '#image');
    }
}