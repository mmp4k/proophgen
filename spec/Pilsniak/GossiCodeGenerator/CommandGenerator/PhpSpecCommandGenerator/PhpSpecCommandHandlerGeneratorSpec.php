<?php

namespace spec\Pilsniak\GossiCodeGenerator\CommandGenerator\PhpSpecCommandGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use Pilsniak\GossiCodeGenerator\CommandGenerator\PhpSpecCommandGenerator\PhpSpecCommandHandlerGenerator;
use PhpSpec\ObjectBehavior;
use Pilsniak\ProophGen\Model\Command;
use Prophecy\Argument;

class PhpSpecCommandHandlerGeneratorSpec extends ObjectBehavior
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

namespace spec\Model\CommandHandler;

use Model\Command\RegisterUser;
use Model\CommandHandler\RegisterUserHandler;
use PhpSpec\ObjectBehavior;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prophecy\Argument;

class RegisterUserHandlerSpec extends ObjectBehavior {

\tpublic function it_is_invoked_by_command(RegisterUser \$registerUser) {
\t\t\$this->__invoke(\$registerUser);
\t}
}
";
        $command = new Command('Model\Command\RegisterUser');
        $this->execute($command)->filename()->shouldBe('../spec/Model/CommandHandler/RegisterUserHandlerSpec.php');
        $this->execute($command)->fileContent()->shouldBe($content);
    }
}
