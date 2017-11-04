<?php

namespace spec\Pilsniak\ProophGen;

use Pilsniak\ComposerGenerator\ComposerJsonGenerator;
use Pilsniak\ProophGen\FileParser;
use Pilsniak\ProophGen\FileSystem;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\ProophGen\ProophGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProophGeneratorSpec extends ObjectBehavior
{
    function let(ProophGenerator\CommandGenerator $commandGenerator,
                 ProophGenerator\AggregateRootGenerator $aggregateRootGenerator,
                 ProophGenerator\ValueObjectGenerator $valueObjectGenerator,
                 ComposerJsonGenerator $composerJsonGenerator)
    {
        $this->beConstructedWith($commandGenerator, $valueObjectGenerator, $aggregateRootGenerator, $composerJsonGenerator);
    }

    function it_generates_files(FileParser $fileParser,
                                FileSystem $fileSystem,
                                ProophGenerator\CommandGenerator $commandGenerator,
                                ProophGenerator\AggregateRootGenerator $aggregateRootGenerator,
                                ProophGenerator\ValueObjectGenerator $valueObjectGenerator,
                                ComposerJsonGenerator $composerJsonGenerator)
    {
        $command = new Command('Model\Command\RegisterUser');
        $event = new Event('UserRegistered');
        $aggregateRoot = new AggregateRoot('Model\User', [$event]);
        $valueObject = new ValueObject('Model\ValueObject\Mail');

        $fileParser->commands()->shouldBeCalled();
        $fileParser->commands()->willReturn([$command]);
        $fileParser->aggregateRoots()->shouldBeCalled();
        $fileParser->aggregateRoots()->willReturn([$aggregateRoot]);
        $fileParser->valueObjects()->shouldBeCalled();
        $fileParser->valueObjects()->willReturn([$valueObject]);

        $aggregateRootGenerator->generate($aggregateRoot)->shouldBeCalled();

        $valueObjectGenerator->generate($valueObject)->shouldBeCalled();
        $valueObjectGenerator->generate($valueObject)->willReturn([
            new FileToSave('./src/Model/ValueObject/Mail.php', 'somecontent')
        ]);

        $commandGenerator->generate($command)->shouldBeCalled();
        $commandGenerator->generate($command)->willReturn([
            new FileToSave('./src/Model/Command/RegisterUser.php', 'somecontent'),
            new FileToSave('./src/Model/CommandHandler/RegisterUserHandler.php', 'somecontent')
        ]);

        $composerJsonGenerator->generate()->shouldBeCalled();
        $composerJsonGenerator->generate()->willReturn(new FileToSave('composer.json', 'json'));

        $fileSystem->save('./src/Model/ValueObject/Mail.php', 'somecontent')->shouldBeCalled();
        $fileSystem->save('./src/Model/Command/RegisterUser.php', 'somecontent')->shouldBeCalled();
        $fileSystem->save('./src/Model/CommandHandler/RegisterUserHandler.php', 'somecontent')->shouldBeCalled();
        $fileSystem->save('composer.json', 'json')->shouldBeCalled();

        $this->generate($fileParser, $fileSystem);
    }
}
