<?php

namespace spec\Pilsniak\YamlFileParser;

use Pilsniak\ProophGen\FileParser;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\IdPolicy;
use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\YamlFileParser\YamlLoader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Pilsniak\ProophGen\Model\IdPolicySpec;

class YamlLoaderSpec extends ObjectBehavior
{
    function let()
    {
        $fileContent = 'idPolicy: Ramsey\Uuid\UuidInterface
valueObjects:
  - Model\ValueObject\Mail
  - Model\ValueObject\Name
  - Model\ValueObject\Password
commands:
  - Model\Command\RegisterUser
  - Model\Command\LoginUser
aggregateRoots:
  Model\User:
    - !UserRegistered
  Model\Identity:
    - !EmailIdentityCreated
    - UserToIdentityAssigned
    - UserLogged
        ';
        $this->beConstructedWith($fileContent);
        $this->shouldImplement(FileParser::class);
    }

    function it_returns_value_objects()
    {
        $this->valueObjects()->shouldBeArray();
        $this->valueObjects()->shouldHaveCount(3);
        $this->valueObjects()[0]->shouldImplement(ValueObject::class);
    }

    function it_returns_commands()
    {
        $this->commands()->shouldBeArray();
        $this->commands()->shouldHaveCount(2);
        $this->commands()[0]->shouldImplement(Command::class);
    }

    function it_returns_aggregate_roots()
    {
        $this->aggregateRoots()->shouldBeArray();
        $this->aggregateRoots()->shouldHaveCount(2);
        $this->aggregateRoots()[0]->shouldImplement(AggregateRoot::class);
        $this->aggregateRoots()[0]->events()->shouldBeArray();
        $this->aggregateRoots()[0]->events()->shouldHaveCount(1);
        $this->aggregateRoots()[0]->events()[0]->shouldImplement(Event::class);
        $this->aggregateRoots()[0]->events()[0]->isCreator()->shouldBe(true);
        $this->aggregateRoots()[1]->events()->shouldBeArray();
        $this->aggregateRoots()[1]->events()->shouldHaveCount(3);
    }

    function it_returns_id_policy()
    {
        $this->idPolicy()->shouldImplement(IdPolicy::class);
    }
}
