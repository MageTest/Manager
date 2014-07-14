<?php
namespace MageTest\Manager;


use Mage;

class AddressTest extends WebTestCase
{
    private $fixtures;
    private $address;
    private $customerId;
    private $customer;

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
            Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
            foreach($this->fixtures as $fixture){
                $fixture->delete();
            }
            Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);
        }
        parent::tearDown();
    }

    public function testAssignAddressToCustomer()
    {
        $email = 'test@example.com';
        $pass = 'qwerty123';

        $this->customerId = $this->fixtures['customer']->create($this->getCustomerAttributes($email, $pass));
        $this->customer = Mage::getModel('customer/customer')->load($this->customerId);

        $this->fixtures['address']->setCustomer($this->customer);

        $testAddress = $this->getAddressAttributes($this->customer);
        $this->address = $this->fixtures['address']->create($testAddress);

        $this->customerLogin($email, $pass);

        $this->assertSession()->pageTextContains($testAddress['postcode']);
    }

    public function testDeleteAddressOfCustomer()
    {
        $email = 'test@example.com';
        $pass = 'qwerty123';

        $this->customerId = $this->fixtures['customer']->create($this->getCustomerAttributes($email, $pass));
        $this->customer = Mage::getModel('customer/customer')->load($this->customerId);

        $this->fixtures['address']->setCustomer($this->customer);

        $testAddress = $this->getAddressAttributes($this->customer);
        $this->address = $this->fixtures['address']->create($testAddress);

        $this->fixtures['address']->delete();

        $this->customerLogin($email, $pass);

        $this->assertSession()->pageTextNotContains($testAddress['postcode']);
    }

}
 