<?php
namespace MageTest\Manager;


class AddressTest extends WebTestCase
{
    private $fixtures;
    private $address;
    private $customerId;

    protected function setUp()
    {
        parent::setUp();
        $this->fixtures = array(
            'address' => new Address(),
            'customer' =>new Customer()
        );
    }

    protected function tearDown()
    {
        if ($this->address && $this->customer) {
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
            foreach($this->fixtures as $fixture){
                $fixture->delete();
            }
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);
        }
    }

    public function testAssignAddressToCustomer()
    {
        $email = 'ever.zet@gmail.com';
        $pass = 'qwerty';

        $this->customerId = $this->fixtures['customer']->create($this->getCustomerAttributes($email, $pass));
        $this->fixtures['address']->setCustomerId($this->customerId);
        $this->address = $this->fixtures['address']->create($this->getAddressAttributes());

        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/customer/account/login');
        $session->getPage()->fillField('Email Address', $email);
        $session->getPage()->fillField('Password', $pass);
        $session->getPage()->pressButton('Login');

        file_put_contents("tmp/" . time() . ".html", $this->getSession()->getPage()->getHtml());
    }

    private function getCustomerAttributes($email, $pass)
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

    private function getAddressAttributes()
    {
        $customer = \Mage::getModel('customer/customer')->load($this->customerId);
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
}
 