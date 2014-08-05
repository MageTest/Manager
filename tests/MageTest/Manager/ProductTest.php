<?php
namespace MageTest\Manager;

class ProductTest extends WebTestCase
{
    private $productFixture;

    protected function setUp()
    {
        parent::setUp();
        $this->productFixture = $this->manager->loadFixture('catalog/product');
    }

    public function testCreateSimpleProduct()
    {
        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $this->productFixture->getId());
        $this->assertSession()->statusCodeEquals(200);
    }

    public function testDeleteSimpleProduct()
    {
        $this->manager->clear();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $this->productFixture->getId());
        $this->assertSession()->statusCodeEquals(404);
    }

    public function testCreateSimpleProductWithImage()
    {
        $imageURL = getcwd() . '/tests/MageTest/Manager/Assets/370x370.jpg';

        $this->productFixture->setMediaGallery (array('images'=>array (), 'values'=>array ()));
        $this->productFixture->addImageToMediaGallery($imageURL, array('image','thumbnail','small_image'), false, false);
        $this->productFixture->save();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $this->productFixture->getId());
        $this->assertSession()->elementExists('css', '#image');
    }
}