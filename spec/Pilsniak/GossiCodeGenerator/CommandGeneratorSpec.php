<?php

namespace spec\Pilsniak\GossiCodeGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\CommandGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\CommandExecuter;
use Pilsniak\ProophGen\Model\Command;
use Prophecy\Argument;

class CommandGeneratorSpec extends ObjectBehavior
{
    function let()
    {
        $generator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);
        $commandHandlerGenerator = new CommandGenerator\CommandHandlerGenerator($generator);
        $commandGenerator = new CommandGenerator\CommandGenerator($generator);

        $this->beConstructedWith($commandHandlerGenerator, $commandGenerator);
    }

    function it_implements_correct_interface()
    {
        $this->shouldImplement(CommandExecuter::class);
    }

    function it_generates_command_and_handler()
    {
        $command = new Command('Model\Command\RegisterUser');
        $response = $this->execute($command);
        $response->shouldBeArray();
        $response->shouldHaveCount(2);

    }
}
