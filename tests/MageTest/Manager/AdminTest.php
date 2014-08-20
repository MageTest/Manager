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

class AdminTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testCreateAdmin()
    {
        $adminFixture = $this->manager->loadFixture('admin/user');

        /*Password from src/MageTest/Manager/Fixtures/Admin.yml due to hashing*/
        $this->adminLogin($adminFixture->getUsername(), 'testadmin123');

        $this->assertSession()->elementExists('css', 'body.adminhtml-dashboard-index');
    }
}
