<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;

class PhpSpecAggregateCode
{
    /**
     * @var CodeFileGenerator
     */
    private $codeFileGenerator;
    /**
     * @var IdStrategy
     */
    private $idStrategy;

    public function __construct(CodeFileGenerator $codeFileGenerator, IdStrategy $idStrategy)
    {
        $this->codeFileGenerator = $codeFileGenerator;
        $this->idStrategy = $idStrategy;
    }

    public function execute(AggregateRoot $aggregateRoot): FileToSave
    {
        return new FileToSave($this->generateFileName($aggregateRoot), $this->generateFileContent($aggregateRoot));
    }

    private function generateFileContent(AggregateRoot $aggregateRoot): string
    {
        $phpClass = new PhpClass();
        $phpClass->setQualifiedName('spec\\'.$aggregateRoot->qualifiedName().'Spec');
        $phpClass->setParentClassName('ObjectBehavior');
        $phpClass->addUseStatement('PhpSpec\ObjectBehavior');
        $phpClass->addUseStatement('Prophecy\Argument');
        $phpClass->addUseStatement($aggregateRoot->qualifiedName());

        $phpClass->setMethod(
            PhpMethod::create('it_returns_id')
                ->setBody($this->idStrategy->convertToString('$this->id()').'->shouldBe('.$this->idStrategy->convertToString('$this->_id()').');')
        );

        foreach ($aggregateRoot->events() as $event) {
            $phpClass->addUseStatement($event->guardQualifiedName($aggregateRoot));
            if ($event->isCreator()) {
                $phpClass->setMethod(
                    PhpMethod::create('let')
                        ->addParameter(PhpParameter::create($event->guardVariableName())->setType($event->guardName()))
                        ->setBody('$this->beConstructedThrough(\''.$event->aggregateMethodName().'\', [$'.$event->guardVariableName().', $this->_id()]);')
                );
            } else {
                $phpClass->setMethod(
                    PhpMethod::create('it_can_' . $event->aggregateMethodName())
                        ->addParameter(PhpParameter::create($event->guardVariableName())->setType($event->guardName()))
                        ->setBody($this->generateBodyForEvent($aggregateRoot, $event))
                );
            }

        }

        $this->idStrategy->phpSpecIdGenerator($phpClass);
        $this->idStrategy->modifyPhpClass($phpClass);
        return $this->codeFileGenerator->generate($phpClass);
    }

    private function generateFileName(AggregateRoot $aggregateRoot): string
    {
        return str_replace(['./src/', '.php'], ['./spec/', 'Spec.php'], $aggregateRoot->path());
    }

    private function generateBodyForEvent(AggregateRoot $aggregateRoot, Event $event): string
    {
        if ($event->isCreator()) {
            $body = '$this->beConstructedThrough(\''.$event->aggregateMethodName().'\', [$this->_id()]);' . "\n";
            $body .= '$this->id()->shouldBe($this->_id());';

            return $body;
        }
        $body = '$this->'.$event->aggregateMethodName().'($'.$event->guardVariableName().');';

        return $body;
    }
}
