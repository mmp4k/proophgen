<?php

namespace spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\GossiCodeGenerator\IdStrategy\StringIdStrategy;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\ProophGenerator\AggregateRootGenerator\PhpSpecAggregateRootExecuter;
use Prophecy\Argument;
use spec\Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecEventGuardSpec;
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

        $idStrategy = new StringIdStrategy();

        $this->beConstructedWith(
            new PhpSpecGenerator\PhpSpecAggregateCode($generator, $idStrategy),
            new PhpSpecGenerator\PhpSpecEventSourced($generator, $idStrategy),
            new PhpSpecGenerator\PhpSpecEvent($generator, $idStrategy),
            new PhpSpecGenerator\PhpSpecExceptionNotFound($generator, $idStrategy),
            new PhpSpecGenerator\PhpSpecInMemoryRepository($generator, $idStrategy),
            new PhpSpecGenerator\PhpSpecEventGuard($generator)
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
        $response->shouldHaveCount(6);
    }
}
