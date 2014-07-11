<?php
namespace MageTest\Manager;

use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\WebAssert;
use Mage_Customer_Model_Customer;
use PHPUnit_Framework_TestCase;

abstract class WebTestCase extends PHPUnit_Framework_Testcase
{
    /**
     * @var \Behat\Mink\Mink
     */
    private $mink;

    protected function setUp()
    {
        $this->mink = new Mink(array(
            'goutte' => new Session(new GoutteDriver())
        ));
        $this->mink->setDefaultSessionName('goutte');
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

    /**
     * @param $email
     * @param $pass
     */
    protected function customerLogin($email, $pass)
    {
        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/customer/account/login');
        $session->getPage()->fillField('Email Address', $email);
        $session->getPage()->fillField('Password', $pass);
        $session->getPage()->pressButton('Login');
    }

    /**
     * @param $email
     * @param $pass
     * @return array
     */
    protected function getCustomerAttributes($email, $pass)
    {
        return array(
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

    /**
     * @return array
     */
    protected function getAddressAttributes(Mage_Customer_Model_Customer $customer)
    {
        return array(
            'firstname' => $customer->getFirstname(),
            'lastname' => $customer->getLastname(),
            'customer_id' => $customer->getId(),
            'company'   => 'Session Digital',
            'street' => 'Brown Street',
            'street1' => 'Brown Street',
            'city' => 'Manchester',
            'postcode' =>  'M2 2JG',
            'region' => 'Lancashire',
            'country' => 'United Kingdom',
            'country_id' => 'GB',
            'telephone' => '1234567890',
            'is_default_billing' => 1,
            'is_default_shipping' => 1,
            'save_in_address_book' => '1',
            'use_for_shipping' => '1'
        );
    }

    /**
     * @return array
     */
    protected function getProductAttributes()
    {
        return array(
            'sku'   => 'test-product-123',
            'name'  => 'test product',
        );
    }
} 