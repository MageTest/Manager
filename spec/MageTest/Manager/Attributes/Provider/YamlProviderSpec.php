<?php

namespace spec\MageTest\Manager\Attributes\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class YamlProviderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(getcwd() . '/fixtures/Customer.yml');
    }

    function it_should_implement_provider_interface()
    {
        $this->shouldImplement('MageTest\Manager\Attributes\Provider\ProviderInterface');
    }

    function it_should_parse_attributes_from_a_yaml_file()
    {
        $this->readAttributes()->shouldReturn(array(
            "firstname" => 'test',
            "lastname" => 'test',
            "email" => 'customer@example.com',
            "password" => '123123pass',
            "confirmation" => '123123pass',
            "website_id" => 1,
            "store" => 1,
            "status" => 1
        ));
    }

    function it_should_get_the_magento_model_from_the_yaml_file()
    {
        $this->getModelType()->shouldReturn('customer/customer');
    }
}
