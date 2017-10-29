<?php

namespace spec\Pilsniak\GossiCodeGenerator\IdStrategy;

use Pilsniak\GossiCodeGenerator\IdStrategy\RamseyUuidIdStrategy;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RamseyUuidIdStrategySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RamseyUuidIdStrategy::class);
    }
}
