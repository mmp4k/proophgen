<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;

class AggregateRootEventGuardGenerator
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
        return new FileToSave($event->guardQualifiedPath($aggregateRoot), $this->getContent($aggregateRoot, $event));
    }

    private function getContent(AggregateRoot $aggregateRoot, Event $event): string
    {
        $class = new PhpClass();
        $class->setQualifiedName($event->guardQualifiedName($aggregateRoot));
        $class->addUseStatement($aggregateRoot->qualifiedName());

        $class->setMethod(
            PhpMethod::create('throwExceptionIfNotPossible')
                ->addParameter(PhpParameter::create($aggregateRoot->variableName())->setType($aggregateRoot->className()))
        );

        return $this->codeFileGenerator->generate($class);
    }
}
