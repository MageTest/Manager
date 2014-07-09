<?php
/**
 * Magenager
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
namespace MageTest\Magenager;

use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\WebAssert;

class CustomerTest extends \PHPUnit_Framework_TestCase
{
    private $mink;
    private $fixtures;
    private $customer;

    protected function setUp()
    {
        $this->mink = new Mink(array(
            'goutte' => new Session(new GoutteDriver())
        ));
        $this->mink->setDefaultSessionName('goutte');

        $this->fixtures = new Customer();
    }

    protected function tearDown()
    {
        if ($this->customer) {
            $this->fixtures->delete($this->customer);
        }
    }

    public function testCreatesCustomerWithEmailAndFirstName()
    {
        $email = 'ever.zet@gmail.com';
        $pass = 'qwerty';

        $this->customer = $this->fixtures->create($this->getCustomerAttributes($email, $pass));

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/customer/account/login');
        $session->getPage()->fillField('Email Address', $email);
        $session->getPage()->fillField('Password', $pass);
        $session->getPage()->pressButton('Login');

        $this->assertSession()->addressEquals('/customer/account/');
    }

    public function testDeletesCustomer()
    {
        $email = 'ever.zet@gmail.com';
        $pass = 'qwerty';

        $this->customer = $this->fixtures->create($this->getCustomerAttributes($email, $pass));
        $this->fixtures->delete($this->customer);

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/customer/account/login');
        $session->getPage()->fillField('Email Address', $email);
        $session->getPage()->fillField('Password', $pass);
        $session->getPage()->pressButton('Login');

        $this->assertSession()->addressEquals('/customer/account/login/');
    }

    /**
     * @param null|string $name
     *
     * @return Session
     */
    protected function getSession($name = null)
    {
        return $this->mink->getSession($name);
    }

    /**
     * @param null|string $name
     *
     * @return WebAssert
     */
    protected function assertSession($name = null)
    {
        return $this->mink->assertSession($name);
    }

    private function getCustomerAttributes($email, $pass)
    {
        return $attributes = array(
            "website_id"   => \Mage::app()->getWebsite()->getId(),
            "store"        => \Mage::app()->getStore(),
            "email"        => $email,
            "firstname"    => "test",
            "lastname"     => "test",
            "password"     => $pass,
            "confirmation" => $pass,
            "status"       => 1
        );
    }
}
