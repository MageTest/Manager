<?php
namespace MageTest\Manager;

class AddressTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $addressfixture = getcwd() . '/src/MageTest/Manager/Fixtures/Address.yml';
        $this->manager->loadFixture($addressfixture);
    }

    public function testAssignAddressToCustomer()
    {
        $address = $this->manager->getFixture('customer/address');
        $customer = $this->manager->getFixture('customer/customer');

        $this->customerLogin($customer->getEmail(), $customer->getPassword());

        $this->assertSession()->pageTextContains($address->getPostcode());
    }

    public function testDeleteAddressOfCustomer()
    {
        $address = $this->manager->getFixture('customer/address');
        $customer = $this->manager->getFixture('customer/customer');

        $this->customerLogin($customer->getEmail(), $customer->getPassword());

        $this->manager->clear();

        $this->assertSession()->pageTextContains($address->getPostcode());
    }
}
 