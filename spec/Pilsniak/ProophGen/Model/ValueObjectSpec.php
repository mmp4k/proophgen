<?php

namespace spec\Pilsniak\ProophGen\Model;

use Pilsniak\ProophGen\Model\ValueObject;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ValueObjectSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Model\ValueObject\Mail');
    }

    function it_returns_path()
    {
        $this->path()->shouldBe('Model/ValueObject/Mail.php');
    }

    function it_returns_class_name()
    {
        $this->className()->shouldBe('Mail');
    }

    function it_returns_namespace()
    {
        $this->namespace()->shouldBe('Model\ValueObject');
    }

    function it_returns_qualified_name()
    {
        $this->qualifiedName()->shouldBe('Model\ValueObject\Mail');
    }
}
