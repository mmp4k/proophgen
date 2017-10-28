<?php

namespace spec\Pilsniak\ProophGen\ProophGenerator;

use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\CommandExecuter;
use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\ProophGenerator\CommandGenerator;
use Pilsniak\ProophGen\ProophGenerator\CommandGenerator\PhpSpecCommandExecuter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandGeneratorSpec extends ObjectBehavior
{
    function let(CommandExecuter $commandExecuter, PhpSpecCommandExecuter $phpSpecCommandExecuter)
    {
        $this->beConstructedWith($commandExecuter, $phpSpecCommandExecuter);
    }

    function it_generates_command(CommandExecuter $commandExecuter, PhpSpecCommandExecuter $phpSpecCommandExecuter)
    {
        $command = new Command('Model\Command\RegisterUser');
        $response = <<<File

File;

        $phpSpecCommandExecuter->execute($command)->shouldBeCalled();
        $phpSpecCommandExecuter->execute($command)->willReturn([new FileToSave('', $response)]);
        $commandExecuter->execute($command)->shouldBeCalled();
        $commandExecuter->execute($command)->willReturn([new FileToSave('', $response)]);

        $fileToSave = $this->generate($command);
        $fileToSave->shouldHaveCount(2);
        $fileToSave[0]->fileContent()->shouldBe($response);
        $fileToSave[1]->fileContent()->shouldBe($response);
    }
}
