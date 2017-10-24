<?php

namespace spec\Pilsniak\GossiCodeGenerator\CommandGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\CommandGenerator\CommandHandlerGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\Command;
use Prophecy\Argument;

class CommandHandlerGeneratorSpec extends ObjectBehavior
{
    function let()
    {
        $generator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);
        $this->beConstructedWith($generator);
    }

    function it_generates_command_handler_code()
    {
        $content = "<?php
declare(strict_types=1);

namespace Model\CommandHandler;

use Model\Command\RegisterUser;

class RegisterUserHandler {

\tpublic function __invoke(RegisterUser \$registerUser): void {
\t\t// TODO: write logic here
\t}
}
";

        $command = new Command('Model\Command\RegisterUser');
        $this->execute($command)->fileContent()->shouldBe($content);
    }
}
