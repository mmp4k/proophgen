<?php

namespace spec\Pilsniak\ProophGen\ProophGenerator;

use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\ProophGenerator\AggregateRootGenerator;
use Pilsniak\ProophGen\ProophGenerator\AggregateRootGenerator\PhpSpecAggregateRootExecuter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Pilsniak\ProophGen\AggregateRootExecuter;

class AggregateRootGeneratorSpec extends ObjectBehavior
{
    function let(AggregateRootExecuter $aggregateRootExecuter, PhpSpecAggregateRootExecuter $phpSpecAggregateRootExecuter)
    {
        $this->beConstructedWith($aggregateRootExecuter, $phpSpecAggregateRootExecuter);
    }

    function it_generates_aggregate_root(AggregateRootExecuter $aggregateRootExecuter, PhpSpecAggregateRootExecuter $phpSpecAggregateRootExecuter)
    {
        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered')]);
        $response = <<<File

File;

        $aggregateRootExecuter->execute($aggregateRoot)->shouldBeCalled();
        $aggregateRootExecuter->execute($aggregateRoot)->willReturn([new FileToSave('', $response)]);
        $phpSpecAggregateRootExecuter->execute($aggregateRoot)->shouldBeCalled();
        $phpSpecAggregateRootExecuter->execute($aggregateRoot)->willReturn([new FileToSave('', $response)]);

        $fileToSave = $this->generate($aggregateRoot);
        $fileToSave[0]->fileContent()->shouldBe($response);
    }
}
