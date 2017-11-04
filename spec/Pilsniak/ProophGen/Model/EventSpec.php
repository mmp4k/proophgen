<?php

namespace spec\Pilsniak\ProophGen\Model;

use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('UserRegistered');
    }

    function it_return_event_name()
    {
        $this->name()->shouldBe('UserRegistered');
    }

    // Guard
    function it_returns_guard_qualified_name()
    {
        $aggregateRoot = new AggregateRoot('Model\\User');

        $this->guardQualifiedName($aggregateRoot)->shouldBe('Model\User\Guard\UserRegisteredGuard');
    }

    function it_returns_guard_path()
    {
        $aggregateRoot = new AggregateRoot('Model\\User');

        $this->guardQualifiedPath($aggregateRoot)->shouldBe('./src/Model/User/Guard/UserRegisteredGuard.php');
    }

    function it_returns_guard_name()
    {
        $this->guardName()->shouldBe('UserRegisteredGuard');
    }
}
