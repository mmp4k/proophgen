<?php

namespace spec\Pilsniak\GossiCodeGenerator\CommandGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\CommandGenerator\CommandGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\Command;
use Prophecy\Argument;

class CommandGeneratorSpec extends ObjectBehavior
{
    function let(IdStrategy $idStrategy)
    {
        $generator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);
        $this->beConstructedWith($generator, $idStrategy);
    }

    function it_generates_command_code(IdStrategy $idStrategy)
    {
        $content = "<?php
declare(strict_types=1);

namespace Model\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

class RegisterUser extends Command implements PayloadConstructable {

\tuse PayloadTrait;

\tpublic static function withData(string \$id): self {
\t\treturn new self(['id' => \$id]);
\t}

\tpublic function id(): string {
\t\treturn \$this->payload['id'];
\t}
}
";

        $idStrategy->type()->willReturn('string');
        $idStrategy->modifyPhpClass(Argument::any())->shouldBeCalled();
        $command = new Command('Model\Command\RegisterUser');
        $this->execute($command)->fileContent()->shouldBe($content);
    }
}
