<?php

namespace Pilsniak\GossiCodeGenerator\CommandGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\Model\FileToSave;

class CommandHandlerGenerator
{
    /**
     * @var CodeFileGenerator
     */
    private $codeFileGenerator;

    public function __construct(CodeFileGenerator $codeFileGenerator)
    {
        $this->codeFileGenerator = $codeFileGenerator;
    }

    public function execute(Command $command) : FileToSave
    {
        return new FileToSave('./src/'.$command->commandHandlerPath(), $this->commandHandlerCode($command));
    }


    private function commandHandlerCode(Command $command): string
    {
        $class = new PhpClass();
        $class->addUseStatement($command->commandQualifiedName());
        $class->setQualifiedName($command->commandHandlerQualifiedName());
        $class->setMethod(
            PhpMethod::create('__invoke')
                ->addParameter(PhpParameter::create($command->commandVariableName())->setType($command->commandName()))
                ->setBody('// TODO: write logic here')
                ->setType('void')
        );

        $generator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);

        return $generator->generate($class);
    }
}
