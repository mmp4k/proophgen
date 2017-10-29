<?php

namespace spec\Pilsniak\GossiCodeGenerator\IdStrategy;

use Pilsniak\GossiCodeGenerator\IdStrategy\StringIdStrategy;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StringIdStrategySpec extends ObjectBehavior
{
    function it_returns_type_string()
    {
        $this->type()->shouldBe('string');
    }

    function it_return_that_same_during_conversion()
    {
        $this->convertToString('expression')->shouldBe('expression');
        $this->convertToType('expression')->shouldBe('expression');
    }
}
