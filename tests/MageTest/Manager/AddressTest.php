<?php
namespace MageTest\Manager;

class AddressTest extends WebTestCase
{
    private $addressFixture;

    protected function setUp()
    {
        parent::setUp();
        $this->addressFixture = $this->manager->loadFixture('customer/address');
    }

    public function testAssignAddressToCustomer()
    {
        $customer = $this->manager->getFixture('customer/customer');

        $this->customerLogin($customer->getEmail(), $customer->getPassword());

        $this->assertSession()->pageTextContains($this->addressFixture->getPostcode());
    }

    public function testDeleteAddressOfCustomer()
    {
        $customer = $this->manager->getFixture('customer/customer');

        $this->customerLogin($customer->getEmail(), $customer->getPassword());

        $this->manager->clear();

        $this->assertSession()->pageTextContains($this->addressFixture->getPostcode());
    }
}
 