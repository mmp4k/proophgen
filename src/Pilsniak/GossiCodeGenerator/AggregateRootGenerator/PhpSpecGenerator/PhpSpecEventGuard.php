<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;

class PhpSpecEventGuard
{
    /**
     * @var CodeFileGenerator
     */
    private $codeFileGenerator;

    public function __construct(CodeFileGenerator $codeFileGenerator)
    {
        $this->codeFileGenerator = $codeFileGenerator;
    }

    public function execute(AggregateRoot $aggregateRoot, Event $event): FileToSave
    {
        return new FileToSave($this->generateFileName($aggregateRoot, $event), $this->generateFileContent($aggregateRoot, $event));
    }


    private function generateFileName(AggregateRoot $aggregateRoot, Event $event): string
    {
        return str_replace(['./src/', '.php'], ['./spec/', 'Spec.php'], $event->guardQualifiedPath($aggregateRoot));
    }

    private function generateFileContent(AggregateRoot $aggregateRoot, Event $event): string
    {
        $phpClass = new PhpClass();
        $phpClass->setQualifiedName('spec\\'. $event->guardQualifiedName($aggregateRoot).'Spec');
        $phpClass->setParentClassName('ObjectBehavior');
        $phpClass->addUseStatement('PhpSpec\ObjectBehavior');
        $phpClass->addUseStatement('Prophecy\Argument');
        $phpClass->addUseStatement($event->guardQualifiedName($aggregateRoot));
        $phpClass->addUseStatement($aggregateRoot->qualifiedName());

        $phpClass->setMethod(
            PhpMethod::create('it_throws_exception')
                ->addParameter(PhpParameter::create($aggregateRoot->variableName())->setType($aggregateRoot->className()))
                ->setBody('$this->throwExceptionIfNotPossible($'.$aggregateRoot->variableName().');')
        );

        $phpClass->setMethod(
            PhpMethod::create('it_does_not_throws_exception')
                ->addParameter(PhpParameter::create($aggregateRoot->variableName())->setType($aggregateRoot->className()))
                ->setBody('$this->throwExceptionIfNotPossible($'.$aggregateRoot->variableName().');')
        );

        return $this->codeFileGenerator->generate($phpClass);
    }
}
