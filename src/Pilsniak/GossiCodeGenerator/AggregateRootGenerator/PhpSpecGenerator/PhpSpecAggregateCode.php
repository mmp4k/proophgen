<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;

class PhpSpecAggregateCode
{
    /**
     * @var CodeFileGenerator
     */
    private $codeFileGenerator;

    public function __construct(CodeFileGenerator $codeFileGenerator)
    {
        $this->codeFileGenerator = $codeFileGenerator;
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
                ->setBody('$this->id()->shouldBe(\'id\');')
        );

        foreach ($aggregateRoot->events() as $event) {
            if ($event->isCreator()) {
                $phpClass->setMethod(
                    PhpMethod::create('let')
                        ->setBody('$this->beConstructedThrough(\''.$event->aggregateMethodName().'\', [\'id\']);')
                );
            } else {
                $phpClass->setMethod(
                    PhpMethod::create('it_can_' . $event->aggregateMethodName())
                        ->setBody($this->generateBodyForEvent($aggregateRoot, $event))
                );
            }

        }

        return $this->codeFileGenerator->generate($phpClass);
    }

    private function generateFileName(AggregateRoot $aggregateRoot): string
    {
        return str_replace(['./src/', '.php'], ['./spec/', 'Spec.php'], $aggregateRoot->path());
    }

    private function generateBodyForEvent(AggregateRoot $aggregateRoot, Event $event): string
    {
        if ($event->isCreator()) {
            $body = '$this->beConstructedThrough(\''.$event->aggregateMethodName().'\', [\'id\']);' . "\n";
            $body .= '$this->id()->shouldBe(\'id\');';

            return $body;
        }
        $body = '$this->'.$event->aggregateMethodName().'();';

        return $body;
    }
}
