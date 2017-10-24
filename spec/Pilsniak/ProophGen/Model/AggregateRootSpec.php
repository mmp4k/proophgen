<?php

namespace spec\Pilsniak\ProophGen\Model;

use Pilsniak\ProophGen\Model\AggregateRoot;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class AggregateRootSpec extends ObjectBehavior
{
    function let()
    {
        $events = [new Event('UserRegistered')];
        $this->beConstructedWith('Model\User', $events);
    }

    function it_returns_events()
    {
        $this->events();
    }

    function it_returns_qualified_name()
    {
        $this->qualifiedName()->shouldBe('Model\User');
    }

    function it_returns_path()
    {
        $this->path()->shouldBe('Model/User.php');
    }

    function it_returns_repository_interface_name()
    {
        $this->repositoryInterfaceName()->shouldBe('UserRepository');
    }

    function it_returns_repository_interface_qualified_name()
    {
        $this->repositoryInterfaceQualifiedName()->shouldBe('Model\\UserRepository');
    }

    function it_returns_repository_path()
    {
        $this->repositoryInterfacePath()->shouldBe('Model/UserRepository.php');
    }
    

    // Exceptions
    function it_returns_exception_path()
    {
        $this->exceptionPath()->shouldBe('Model/User/Exception/UserNotFound.php');
    }
}
