<?php
namespace MageTest\Manager;

Use MageTest\Manager\Builders\CustomerBuilder;
Use MageTest\Manager\Builders\AddressBuilder;

class AddressTest extends WebTestCase
{
    private $builders;

    protected function setUp()
    {
        parent::setUp();
        $this->builders = array(
            'address' => new AddressBuilder,
            'customer' => new CustomerBuilder
        );
    }

    public function testAssignAddressToCustomer()
    {
        $customer = $this->manager->create('customer', $this->builders['customer']);
        $address = $this->manager->create('address', $this->builders['address']->withCustomer($customer));

        $this->customerLogin($customer->getEmail(), $customer->getPassword());

        $this->assertSession()->pageTextContains($address->getPostcode());
    }

    public function testDeleteAddressOfCustomer()
    {
        $customer = $this->manager->create('customer', $this->builders['customer']);
        $address = $this->manager->create('address', $this->builders['address']->withCustomer($customer));

        $postcode = $address->getPostcode();

        $this->manager->clear();

        $this->customerLogin($customer->getEmail(), $customer->getPassword());

        $this->assertSession()->pageTextNotContains($postcode);
    }
}
 