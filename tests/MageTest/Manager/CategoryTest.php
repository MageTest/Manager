<?php
namespace MageTest\Manager;

class CategoryTest extends WebTestCase
{
    private $fixtures;
    private $category;

    protected function setUp()
    {
        parent::setUp();
        $this->fixtures = new Category();
    }

    protected function tearDown()
    {
        if ($this->category) {
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
            $this->fixtures->delete();
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);
        }
        parent::tearDown();
    }

    public function testCreateCategory()
    {
        $attributes = $this->getCategoryAttributes();
        $this->category = $this->fixtures->create($attributes);

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/category/view/id/' . $this->category->getModel()->getId());
        $this->assertSession()->statusCodeEquals(200);
    }

    public function testDeleteCategory()
    {
        $attributes = $this->getCategoryAttributes();
        $this->category = $this->fixtures->create($attributes);

        $this->fixtures->delete();

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/catalog/category/view/id/' . $this->category->getModel()->getId());

        $this->assertSession()->statusCodeEquals(404);
    }

    private function getCategoryAttributes()
    {
        return array(
            "name" => "Test Category"
        );
    }
}
 