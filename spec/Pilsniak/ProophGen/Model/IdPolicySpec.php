<?php

namespace spec\Pilsniak\ProophGen\Model;

use Pilsniak\ProophGen\Model\IdPolicy;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IdPolicySpec extends ObjectBehavior
{
    function it_creates_id_as_string()
    {
        $this->beConstructedWith('string');
        $this->name()->shouldBe('string');
    }

    function it_creates_id_as_uuid_ramsey()
    {
        $this->beConstructedWith('Ramsey\Uuid\UuidInterface');
        $this->name()->shouldBe('Ramsey\Uuid\UuidInterface');
    }

    function it_does_not_support_nothing_else()
    {
        $this->beConstructedWith('something_not_supported');
        $this->shouldThrow(\Exception::class)->duringInstantiation();
    }
}
