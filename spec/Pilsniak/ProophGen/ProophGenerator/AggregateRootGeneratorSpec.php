<?php

namespace spec\Pilsniak\ProophGen\ProophGenerator;

use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\ProophGenerator\AggregateRootGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Pilsniak\ProophGen\AggregateRootExecuter;

class AggregateRootGeneratorSpec extends ObjectBehavior
{
    function let(AggregateRootExecuter $aggregateRootExecuter)
    {
        $this->beConstructedWith($aggregateRootExecuter);
    }

    function it_generates_aggregate_root(AggregateRootExecuter $aggregateRootExecuter)
    {
        $aggregateRoot = new AggregateRoot('Model\User', [new Event('UserRegistered')]);
        $response = <<<File

File;

        $aggregateRootExecuter->execute($aggregateRoot)->shouldBeCalled();
        $aggregateRootExecuter->execute($aggregateRoot)->willReturn([new FileToSave('', $response)]);

        $fileToSave = $this->generate($aggregateRoot);
        $fileToSave[0]->fileContent()->shouldBe($response);
    }
}
