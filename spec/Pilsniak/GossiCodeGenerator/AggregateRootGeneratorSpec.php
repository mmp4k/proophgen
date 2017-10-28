<?php

namespace spec\Pilsniak\GossiCodeGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\AggregateRootExecuter;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Prophecy\Argument;

class AggregateRootGeneratorSpec extends ObjectBehavior
{
    function let()
    {
        $generator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);
        $aggregateRootCodeGenerator = new AggregateRootGenerator\AggregateRootCodeGenerator($generator);
        $exceptionNotFoundGenerator = new AggregateRootGenerator\AggregateRootExceptionNotFoundGenerator($generator);
        $eventGenerator = new AggregateRootGenerator\AggregateRootEventGenerator($generator);
        $repositoryInterfaceGenerator = new AggregateRootGenerator\AggregateRootRepositoryInterfaceGenerator($generator);
        $repositoryInMemoryGenerator = new AggregateRootGenerator\AggregateRootInMemoryRepository($generator);
        $repositoryEventSourcedGenerator = new AggregateRootGenerator\AggregateRootEventSourcedRepository($generator);

        $this->beConstructedWith($aggregateRootCodeGenerator, $exceptionNotFoundGenerator, $eventGenerator, $repositoryInterfaceGenerator, $repositoryInMemoryGenerator, $repositoryEventSourcedGenerator);
    }

    function it_implements_correct_interface(CodeFileGenerator $codeFileGenerator)
    {
        $this->shouldImplement(AggregateRootExecuter::class);
    }

    function it_generates_aggregate_root()
    {
        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered')]);
        $response = $this->execute($aggregateRoot);
        $response->shouldBeArray();
        $response[0]->filename()->shouldBe('./src/Model/User.php');
        $response[1]->filename()->shouldBe('./src/Model/UserRepository.php');
        $response[2]->filename()->shouldBe('./src/Model/User/Exception/UserNotFound.php');
        $response[3]->filename()->shouldBe('./src/Model/User/Event/UserRegistered.php');
        $response[4]->filename()->shouldBe('./src/Infrastructure/User/InMemory.php');
        $response[5]->filename()->shouldBe('./src/Infrastructure/User/EventSourced.php');
        $response->shouldHaveCount(6);
    }

}
