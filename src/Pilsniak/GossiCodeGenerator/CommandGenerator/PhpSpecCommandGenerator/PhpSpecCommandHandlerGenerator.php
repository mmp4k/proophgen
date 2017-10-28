<?php

namespace Pilsniak\GossiCodeGenerator\CommandGenerator\PhpSpecCommandGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\Model\FileToSave;

class PhpSpecCommandHandlerGenerator
{
    /**
     * @var CodeFileGenerator
     */
    private $codeFileGenerator;

    public function __construct(CodeFileGenerator $codeFileGenerator)
    {
        $this->codeFileGenerator = $codeFileGenerator;
    }

    public function execute(Command $command): FileToSave
    {
        return new FileToSave($this->generateFileName($command), $this->generateFileContent($command));
    }

    private function generateFileName(Command $command): string
    {
        $prefix = './spec/' . $command->commandHandlerPath();

        return str_replace('.php', 'Spec.php', $prefix);
    }

    private function generateFileContent(Command $command): string
    {
        $phpClass = new PhpClass();
        $phpClass->setQualifiedName('spec\\' . $command->commandHandlerQualifiedName() . 'Spec');
        $phpClass->setParentClassName('ObjectBehavior');
        $phpClass->addUseStatement('PhpSpec\ObjectBehavior');
        $phpClass->addUseStatement('Prophecy\Argument');
        $phpClass->addUseStatement('Prooph\Common\Messaging\Command');
        $phpClass->addUseStatement('Prooph\Common\Messaging\PayloadConstructable');
        $phpClass->addUseStatement($command->commandQualifiedName());
        $phpClass->addUseStatement($command->commandHandlerQualifiedName());

        $phpClass->setMethod(
            PhpMethod::create('it_is_invoked_by_command')
                ->addParameter(PhpParameter::create($command->commandVariableName())->setType($command->commandName()))
                ->setBody('$this->__invoke($'.$command->commandVariableName().');')
        );

        return $this->codeFileGenerator->generate($phpClass);
    }
}
