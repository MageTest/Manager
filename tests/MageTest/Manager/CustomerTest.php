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

Use MageTest\Manager\Builders\CustomerBuilder;

class CustomerTest extends WebTestCase
{
    private $customer;
    private $builder;

    protected function setUp()
    {
        parent::setUp();
        $this->builder = new CustomerBuilder();
    }

    public function testCreatesCustomer()
    {
        $this->customer = $this->manager->create('customer', $this->builder);

        $this->customerLogin($this->customer->getEmail(), $this->customer->getPassword());

        $this->assertSession()->addressEquals('/customer/account/');
    }

    public function testDeletesCustomer()
    {
        $this->customer = $this->manager->create('customer', $this->builder);

        $this->manager->clear();

        $this->customerLogin($this->customer->getEmail(), $this->customer->getPassword());

        $this->assertSession()->addressEquals('/customer/account/login/');
    }
}
