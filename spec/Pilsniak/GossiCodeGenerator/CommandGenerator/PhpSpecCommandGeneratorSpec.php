<?php

namespace spec\Pilsniak\GossiCodeGenerator\CommandGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\CommandGenerator\PhpSpecCommandGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\ProophGenerator\CommandGenerator\PhpSpecCommandExecuter;
use Prophecy\Argument;

class PhpSpecCommandGeneratorSpec extends ObjectBehavior
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
            new PhpSpecCommandGenerator\PhpSpecCommandGenerator($generator),
            new PhpSpecCommandGenerator\PhpSpecCommandHandlerGenerator($generator));
    }

    function it_implements_correct_interface()
    {
        $this->shouldImplement(PhpSpecCommandExecuter::class);
    }

    function it_generates_phpspec_for_command_and_handler()
    {
        $command = new Command('Model\Command\RegisterUser');
        $response = $this->execute($command);
        $response->shouldBeArray();
        $response->shouldHaveCount(2);
    }
}
