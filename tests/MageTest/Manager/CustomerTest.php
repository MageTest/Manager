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

class CustomerTest extends WebTestCase
{
    private $customerFixture;

    protected function setUp()
    {
        parent::setUp();
    }

    public function testCreatesCustomerDefault()
    {
        $this->customerFixture = $this->manager->loadFixture('customer/customer');

        $this->customerLogin($this->customerFixture->getEmail(), $this->customerFixture->getPassword());

        $this->assertSession()->addressEquals('/customer/account/');
    }

    public function testDeletesCustomerDefault()
    {
        $this->customerFixture = $this->manager->loadFixture('customer/customer');

        $this->manager->clear();

        $this->customerLogin($this->customerFixture->getEmail(), $this->customerFixture->getPassword());

        $this->assertSession()->addressEquals('/customer/account/login/');
    }

    public function testCreatesUserDefinedCustomer()
    {
        $this->customerFixture = $this->manager->loadFixture('customer/customer',
            getcwd() . '/tests/MageTest/Manager/Fixtures/Customer.yml');

        $this->customerLogin($this->customerFixture->getEmail(), $this->customerFixture->getPassword());

        $this->assertSession()->addressEquals('/customer/account/');
    }
}
