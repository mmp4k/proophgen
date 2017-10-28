<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\ProophGenerator\AggregateRootGenerator\PhpSpecAggregateRootExecuter;
use Prophecy\Argument;
use spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecExceptionNotFoundSpec;

class PhpSpecGeneratorSpec extends ObjectBehavior
{
    function let()
    {
        $generator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);

        $this->beConstructedWith(
            new PhpSpecGenerator\PhpSpecAggregateCode($generator),
            new PhpSpecGenerator\PhpSpecEventSourced($generator),
            new PhpSpecGenerator\PhpSpecEvent($generator),
            new PhpSpecGenerator\PhpSpecExceptionNotFound($generator),
            new PhpSpecGenerator\PhpSpecInMemoryRepository($generator)
        );
    }

    function it_implements_correct_interface()
    {
        $this->shouldImplement(PhpSpecAggregateRootExecuter::class);
    }

    function it_generates_phpspec_files_for_aggregate_root()
    {
        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered')]);
        $response = $this->execute($aggregateRoot);
        $response->shouldBeArray();
        $response->shouldHaveCount(5);
    }
}
