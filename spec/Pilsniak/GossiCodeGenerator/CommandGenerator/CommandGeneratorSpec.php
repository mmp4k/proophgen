<?php

namespace spec\Pilsniak\GossiCodeGenerator\CommandGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\CommandGenerator\CommandGenerator;
use PhpSpec\ObjectBehavior;
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
        $this->beConstructedWith($generator);
    }

    function it_generates_command_code()
    {
        $content = "<?php
declare(strict_types=1);

namespace Model\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

class RegisterUser extends Command implements PayloadConstructable {

\tuse PayloadTrait;

\tpublic static function withData(): self {
\t\treturn new self([]);
\t}
}
";

        $command = new Command('Model\Command\RegisterUser');
        $this->execute($command)->fileContent()->shouldBe($content);
    }
}
