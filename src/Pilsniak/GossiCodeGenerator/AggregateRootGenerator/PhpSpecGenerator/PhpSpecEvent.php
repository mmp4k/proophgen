<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;

class PhpSpecEvent
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

    public function execute(AggregateRoot $aggregateRoot, Event $event): FileToSave
    {
        return new FileToSave($this->generateFileName($aggregateRoot, $event), $this->generateFileContent($aggregateRoot, $event));
    }

    private function generateFileContent(AggregateRoot $aggregateRoot, Event $event): string
    {
        $phpClass = new PhpClass();
        $phpClass->setQualifiedName('spec\\'. $aggregateRoot->eventNamespace($event).'Spec');
        $phpClass->setParentClassName('ObjectBehavior');
        $phpClass->addUseStatement('PhpSpec\ObjectBehavior');
        $phpClass->addUseStatement('Prophecy\Argument');
        $phpClass->addUseStatement($aggregateRoot->eventNamespace($event));

        $phpClass->setMethod(
            PhpMethod::create('it_is_created_with_id')
                ->setBody($this->generateBodyForCreateWithIdMethod($aggregateRoot, $event))
        );

        $this->idStrategy->modifyPhpClass($phpClass);
        $this->idStrategy->phpSpecIdGenerator($phpClass);
        return $this->codeFileGenerator->generate($phpClass);
    }

    private function generateFileName(AggregateRoot $aggregateRoot, Event $event): string
    {
        return str_replace(['./src/', '.php'], ['./spec/', 'Spec.php'], $aggregateRoot->eventPath($event));
    }

    private function generateBodyForCreateWithIdMethod(AggregateRoot $aggregateRoot, Event $event): string
    {
        $body = '$this->beConstructedThrough(\'create\', [$this->_id()]);' . "\n";
        $body .= '$this->aggregateId()->shouldBe(\'id\');';

        return $body;
    }
}
