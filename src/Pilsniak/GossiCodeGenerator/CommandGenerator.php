<?php

namespace Pilsniak\GossiCodeGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpInterface;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpTrait;
use Pilsniak\GossiCodeGenerator\CommandGenerator\CommandHandlerGenerator;
use Pilsniak\ProophGen\CommandExecuter;
use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\Model\FileToSave;

class CommandGenerator implements CommandExecuter
{
    /**
     * @var CommandHandlerGenerator
     */
    private $commandHandlerGenerator;
    /**
     * @var CommandGenerator\CommandGenerator
     */
    private $commandGenerator;

    public function __construct(CommandHandlerGenerator $commandHandlerGenerator,
                                CommandGenerator\CommandGenerator $commandGenerator)
    {
        $this->commandHandlerGenerator = $commandHandlerGenerator;
        $this->commandGenerator = $commandGenerator;
    }

    public function execute(Command $command): array
    {
        return [
            $this->commandGenerator->execute($command),
            $this->commandHandlerGenerator->execute($command)
        ];
    }

}
