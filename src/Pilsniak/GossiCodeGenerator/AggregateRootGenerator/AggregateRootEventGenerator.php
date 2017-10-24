<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;

class AggregateRootEventGenerator
{
    /**
     * @var CodeFileGenerator
     */
    private $codeGenerator;

    public function __construct(CodeFileGenerator $codeGenerator)
    {
        $this->codeGenerator = $codeGenerator;
    }

    public function execute(AggregateRoot $aggregateRoot, Event $event): FileToSave
    {
        return new FileToSave($aggregateRoot->eventPath($event), $this->getContentAggregateRootEvent($aggregateRoot, $event));
    }

    private function getContentAggregateRootEvent(AggregateRoot $aggregateRoot, Event $event): string
    {
        $class = new PhpClass($aggregateRoot->eventNamespace($event));
        $class->setParentClassName('AggregateChanged');
        $class->addUseStatement('Prooph\EventSourcing\AggregateChanged');
        $class->setMethod(
            PhpMethod::create('create')
                ->addParameter(PhpParameter::create('id')->setType('string'))
                ->setStatic(true)
                ->setType('self')
                ->setBody('return self::occur($id, []);')
        );

        return $this->codeGenerator->generate($class);
    }
}
