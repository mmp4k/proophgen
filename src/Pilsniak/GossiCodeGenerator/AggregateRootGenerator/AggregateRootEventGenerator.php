<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;

class AggregateRootEventGenerator
{
    /**
     * @var CodeFileGenerator
     */
    private $codeGenerator;
    /**
     * @var IdStrategy
     */
    private $idStrategy;

    public function __construct(CodeFileGenerator $codeGenerator, IdStrategy $idStrategy)
    {
        $this->codeGenerator = $codeGenerator;
        $this->idStrategy = $idStrategy;
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
                ->addParameter(PhpParameter::create('id')->setType($this->idStrategy->type()))
                ->setStatic(true)
                ->setType('self')
                ->setBody('return self::occur('.$this->idStrategy->convertToString('$id').', []);')
        );

        if ($event->isCreator()) {
            $class->setMethod(
                PhpMethod::create('id')
                    ->setType($this->idStrategy->type())
                    ->setBody('return '.$this->idStrategy->convertToType('$this->aggregateId()').';')
            );
        }

        $this->idStrategy->modifyPhpClass($class);
        return $this->codeGenerator->generate($class);
    }
}
