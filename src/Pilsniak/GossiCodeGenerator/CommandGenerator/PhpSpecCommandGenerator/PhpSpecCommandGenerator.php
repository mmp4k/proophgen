<?php

namespace Pilsniak\GossiCodeGenerator\CommandGenerator\PhpSpecCommandGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\Model\FileToSave;

class PhpSpecCommandGenerator
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
        $prefix = '../spec/' . $command->commandPath();

        return str_replace('.php', 'Spec.php', $prefix);
    }

    private function generateFileContent(Command $command): string
    {
        $phpClass = new PhpClass();
        $phpClass->setQualifiedName('spec\\' . $command->commandQualifiedName() . 'Spec');
        $phpClass->setParentClassName('ObjectBehavior');
        $phpClass->addUseStatement('PhpSpec\ObjectBehavior');
        $phpClass->addUseStatement('Prophecy\Argument');
        $phpClass->addUseStatement('Prooph\Common\Messaging\Command');
        $phpClass->addUseStatement('Prooph\Common\Messaging\PayloadConstructable');
        $phpClass->addUseStatement($command->commandQualifiedName());

        $phpClass->setMethod(
            PhpMethod::create('it_is_created_by_with_data')
                ->setBody($this->generateBody($command))
        );

        return $this->codeFileGenerator->generate($phpClass);
    }

    private function generateBody(Command $command): string
    {
        $body = '$this->beConstructedThrough(\'withData\');' . "\n";
        $body .= '$this->shouldHaveType(' . $command->commandName() . '::class);' . "\n";
        $body .= '$this->shouldImplement(PayloadConstructable::class);' . "\n";
        $body .= '$this->shouldImplement(Command::class);' . "\n";

        return $body;
    }
}
