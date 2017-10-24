<?php

namespace spec\Pilsniak\ProophGen\ValueObject;

use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\ProophGen\ValueObject\VariableNameGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VariableNameGeneratorSpec extends ObjectBehavior
{
    function let()
    {
        $valueObject = new ValueObject('Model\ValueObject\Mail');
        $this->beConstructedWith($valueObject);
    }

    function it_returns_variable_name()
    {
        $this->variableName()->shouldBe('mail');
        $this->variable()->shouldBe('$mail');
        $this->property()->shouldBe('$this->mail');
    }
}
