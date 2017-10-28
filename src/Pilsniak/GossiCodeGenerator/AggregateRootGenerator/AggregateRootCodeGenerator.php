<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;

class AggregateRootCodeGenerator
{
    /**
     * @var CodeFileGenerator
     */
    private $codeGenerator;

    public function __construct(CodeFileGenerator $codeGenerator)
    {
        $this->codeGenerator = $codeGenerator;
    }

    public function execute(AggregateRoot $aggregateRoot): FileToSave
    {
        return new FileToSave($aggregateRoot->path(), $this->getContentAggregateRoot($aggregateRoot));
    }

    private function whenEventMethod(Event $event)
    {
        return 'when'.$event->name();
    }

    private function generateBodyForApplyMethod(AggregateRoot $aggregateRoot): string
    {
        $code = 'switch (get_class($event)) {' . "\n";
        foreach ($aggregateRoot->events() as $event) {
            /** @var Event $event */
            $code .= "\tcase " . $event->name() . '::class:' . "\n";
            $code .= "\t\t" . '$this->' . $this->whenEventMethod($event) . '($event);' . "\n";
            $code .= "\t\tbreak;\n";
        }
        $code .= '}';
        return $code;
    }


    private function getContentAggregateRoot(AggregateRoot $aggregateRoot): string
    {
        $class = new PhpClass();
        $class->setQualifiedName($aggregateRoot->qualifiedName());
        $class->setParentClassName('AggregateRoot');
        $class->addUseStatement('Prooph\EventSourcing\AggregateChanged');
        $class->addUseStatement('Prooph\EventSourcing\AggregateRoot');
        $class->setProperty(
            PhpProperty::create('id')
                ->setVisibility('private')
        );
        $class->setMethod(
            PhpMethod::create('id')
                ->setType('string')
                ->setBody('return $this->id;')
        );
        $class->setMethod(
            PhpMethod::create('aggregateId')
                ->setBody('return $this->id;')
                ->setType('string')
                ->setVisibility('protected')
        );
        $class->setMethod(
            PhpMethod::create('apply')
                ->setVisibility('protected')
                ->setType('void')
                ->setBody($this->generateBodyForApplyMethod($aggregateRoot))
                ->addParameter(PhpParameter::create('event')->setType('AggregateChanged'))
        );

        foreach ($aggregateRoot->events() as $event) {
            if ($event->isCreator()) {
                $this->creatorEventMethod($class, $event);
            } else {
                $this->defaultEventMethod($class, $event);
            }
            $class->addUseStatement($aggregateRoot->eventNamespace($event));
            $class->addUseStatement($event->guardQualifiedName($aggregateRoot));

            $class->setMethod(
                PhpMethod::create($this->whenEventMethod($event))
                    ->addParameter(PhpParameter::create('event')->setType($event->name()))
                    ->setType('void')
                    ->setVisibility('private')
                    ->setBody($this->generateBodyForWhenMethod($event))
            );
        }

        return $this->codeGenerator->generate($class);
    }

    private function creatorEventMethod(PhpClass $class, Event $event)
    {
        $body = '$' . $event->guardVariableName().'->throwExceptionIfNotPossible();' . "\n\n";
        $body .= '$self = new self;' . "\n";
        $body .= '$self->recordThat('.$event->name().'::create($id));'."\n";
        $body .= 'return $self;';

        $class->setMethod(
            PhpMethod::create($event->aggregateMethodName())
                ->setStatic(true)
                ->addParameter(PhpParameter::create($event->guardVariableName())->setType($event->guardName()))
                ->addParameter(PhpParameter::create('id')->setType('string'))
                ->setBody($body)
                ->setType('self')
        );
    }

    private function defaultEventMethod(PhpClass $class, Event $event)
    {
        $body = '$' . $event->guardVariableName().'->throwExceptionIfNotPossible($this);' . "\n\n";
        $body .= '$this->recordThat('.$event->name().'::create($this->id));';
        $class->setMethod(
            PhpMethod::create($event->aggregateMethodName())
                ->addParameter(PhpParameter::create($event->guardVariableName())->setType($event->guardName()))
                ->setBody($body)
                ->setType('void')
        );
    }

    private function generateBodyForWhenMethod(Event $event): string
    {
        if (!$event->isCreator()) {
            return '';
        }
        return '$this->id = $event->aggregateId();';
    }
}
