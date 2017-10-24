<?php

namespace Pilsniak\GossiCodeGenerator\CommandGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpInterface;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpTrait;
use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\Model\FileToSave;

class CommandGenerator
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
        return new FileToSave($command->commandPath(), $this->commandCode($command));
    }

    private function commandCode(Command $command): string
    {
        $class = new PhpClass();
        $class->setParentClassName('Command');
        $class->addUseStatement('Prooph\Common\Messaging\Command');
        $class->addUseStatement('Prooph\Common\Messaging\PayloadConstructable');
        $class->addUseStatement('Prooph\Common\Messaging\PayloadTrait');
        $class->addInterface(PhpInterface::create('PayloadConstructable'));
        $class->setQualifiedName($command->commandQualifiedName());
        $class->setTraits([PhpTrait::create('Prooph\Common\Messaging\PayloadTrait')]);
        $class->setMethod(
            PhpMethod::create('withData')
                ->setStatic(true)
                ->setBody('return new self([]);')
                ->setType('self')
        );

        return $this->codeFileGenerator->generate($class);
    }
}
