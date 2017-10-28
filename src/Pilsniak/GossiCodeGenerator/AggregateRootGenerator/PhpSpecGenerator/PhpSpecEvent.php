<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;

class PhpSpecEvent
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

        return $this->codeFileGenerator->generate($phpClass);
    }

    private function generateFileName(AggregateRoot $aggregateRoot, Event $event): string
    {
        return str_replace(['./src/', '.php'], ['./spec/', 'Spec.php'], $aggregateRoot->eventPath($event));
    }

    private function generateBodyForCreateWithIdMethod(AggregateRoot $aggregateRoot, Event $event): string
    {
        $body = '$this->beConstructedThrough(\'create\', [\'id\']);' . "\n";
        $body .= '$this->aggregateId()->shouldBe(\'id\');';

        return $body;
    }
}
