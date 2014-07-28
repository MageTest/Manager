<?php

namespace spec\MageTest\Manager;

use MageTest\Manager\BuilderManager;
use MageTest\Manager\Builders\BuilderInterface;
use MageTest\Manager\Builders\CustomerBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FixtureManagerSpec extends ObjectBehavior
{
    function it_should_be_constructed_without_fixtures()
    {
        $this->beConstructedWith();
        $this->shouldThrow('\InvalidArgumentException')->duringGetFixture('invalid');
    }
}
