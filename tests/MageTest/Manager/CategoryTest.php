<?php
/**
 * Manager
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, that is bundled with this
 * package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://opensource.org/licenses/MIT
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email
 * to <magetest@sessiondigital.com> so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2014 MageTest team and contributors.
 */
namespace MageTest\Manager;

class CategoryTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testCreateCategory()
    {
        $categoryFixture = $this->manager->loadFixture('catalog/category', __DIR__ . '/Fixtures/Category.yml');

        $this->getSession()->visit(getenv('BASE_URL') . '/catalog/category/view/id/' . $categoryFixture->getId());

        $this->assertSession()->statusCodeEquals(200);
    }
}
