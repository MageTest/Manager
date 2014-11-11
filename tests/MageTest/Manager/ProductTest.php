<?php
namespace MageTest\Manager;

class ProductTest extends WebTestCase
{
    private $productFixture;

    protected function setUp()
    {
        parent::setUp();

    }

    public function testCreateSimpleProduct()
    {
        $this->productFixture = $this->manager->loadFixture('catalog/product');

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $this->productFixture->getId());
        $this->assertSession()->statusCodeEquals(200);
    }

    public function testDeleteSimpleProduct()
    {
        $this->productFixture = $this->manager->loadFixture('catalog/product');

        $this->manager->clear();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $this->productFixture->getId());
        $this->assertSession()->statusCodeEquals(404);
    }

    public function testCreateSimpleProductWithImage()
    {
        $this->productFixture = $this->manager->loadFixture('catalog/product');

        $imageURL = getcwd() . '/tests/MageTest/Manager/Assets/370x370.jpg';

        $this->productFixture->setMediaGallery (array('images'=>array (), 'values'=>array ()));
        $this->productFixture->addImageToMediaGallery($imageURL, array('image','thumbnail','small_image'), false, false);
        $this->productFixture->save();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $this->productFixture->getId());
        $this->assertSession()->elementExists('css', '#image');
    }

    public function testOverrideDefaultValues()
    {
        $this->productFixture = $this->manager->loadFixture('catalog/product', null, array(
            'name' => 'Overridden Product Name',
        ));

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/product/view/id/' . $this->productFixture->getId());

        $this->assertSession()->pageTextContains('Overridden Product Name');
        $this->assertSession()->statusCodeEquals(200);
    }
}