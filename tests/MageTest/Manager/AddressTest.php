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
        $customer = $this->addressFixture->getCustomer();

        //hard coded due to hashing
        $this->customerLogin($customer->getEmail(), '123123pass');

        $this->assertSession()->pageTextContains($this->addressFixture->getPostcode());
    }

    public function testDeleteAddressOfCustomer()
    {
        $customer = $this->addressFixture->getCustomer();

        //hard coded due to hashing
        $this->customerLogin($customer->getEmail(), '123123pass');

        $this->manager->clear();

        $this->assertSession()->pageTextContains($this->addressFixture->getPostcode());
    }
}
 