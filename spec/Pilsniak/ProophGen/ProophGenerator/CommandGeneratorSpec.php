<?php

namespace spec\Pilsniak\ProophGen\ProophGenerator;

use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\CommandExecuter;
use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\ProophGenerator\CommandGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandGeneratorSpec extends ObjectBehavior
{
    function let(CommandExecuter $commandExecuter)
    {
        $this->beConstructedWith($commandExecuter);
    }

    function it_generates_command(CommandExecuter $commandExecuter, Command $command)
    {
        $command = new Command('Model\Command\RegisterUser');
        $response = <<<File

File;

        $commandExecuter->execute($command)->shouldBeCalled();
        $commandExecuter->execute($command)->willReturn([new FileToSave('', $response)]);

        $fileToSave = $this->generate($command);
        $fileToSave[0]->fileContent()->shouldBe($response);
    }
}
