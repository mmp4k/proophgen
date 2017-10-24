<?php

namespace spec\Pilsniak\ProophGen\Model;

use Pilsniak\ProophGen\Model\Command;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Model\Command\RegisterUser');
    }

    function it_return_command_name()
    {
        $this->commandName()->shouldBe('RegisterUser');
        $this->commandVariableName()->shouldBe('registerUser');
        $this->commandPath()->shouldBe('Model/Command/RegisterUser.php');
        $this->commandQualifiedName()->shouldBe('Model\Command\RegisterUser');
    }

    function it_return_command_handler_name()
    {
        $this->commandHandlerName()->shouldBe('RegisterUserHandler');
        $this->commandHandlerPath()->shouldBe('Model/CommandHandler/RegisterUserHandler.php');
        $this->commandHandlerQualifiedName()->shouldBe('Model\CommandHandler\RegisterUserHandler');
    }

}
