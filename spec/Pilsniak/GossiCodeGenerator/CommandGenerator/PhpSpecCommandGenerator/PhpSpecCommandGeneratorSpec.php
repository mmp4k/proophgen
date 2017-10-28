<?php

namespace spec\Pilsniak\GossiCodeGenerator\CommandGenerator\PhpSpecCommandGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\CommandGenerator\PhpSpecCommandGenerator\PhpSpecCommandGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\Command;
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
        $this->beConstructedWith($generator);
    }

    function it_generates_code()
    {
        $content = "<?php
declare(strict_types=1);

namespace spec\Model\Command;

use Model\Command\RegisterUser;
use PhpSpec\ObjectBehavior;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prophecy\Argument;

class RegisterUserSpec extends ObjectBehavior {

\tpublic function it_is_created_by_with_data() {
\t\t\$this->beConstructedThrough('withData');
\t\t\$this->shouldHaveType(RegisterUser::class);
\t\t\$this->shouldImplement(PayloadConstructable::class);
\t\t\$this->shouldImplement(Command::class);
\t}
}
";
        $command = new Command('Model\Command\RegisterUser');
        $this->execute($command)->filename()->shouldBe('../spec/Model/Command/RegisterUserSpec.php');
        $this->execute($command)->fileContent()->shouldBe($content);
    }
}
