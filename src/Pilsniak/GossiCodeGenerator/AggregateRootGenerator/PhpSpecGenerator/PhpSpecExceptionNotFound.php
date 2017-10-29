<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\FileToSave;

class PhpSpecExceptionNotFound
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
        $phpClass->setQualifiedName('spec\\'.$aggregateRoot->exceptionQualifiedName().'Spec');
        $phpClass->setParentClassName('ObjectBehavior');
        $phpClass->addUseStatement('PhpSpec\ObjectBehavior');
        $phpClass->addUseStatement('Prophecy\Argument');
        $phpClass->addUseStatement($aggregateRoot->exceptionQualifiedName());

        $phpClass->setMethod(
            PhpMethod::create('it_is_domain_exception')
                ->setBody('$this->shouldImplement(\DomainException::class);')
        );

        $phpClass->setMethod(
            PhpMethod::create('it_is_created_with_id')
                ->setBody($this->generateBodyForCreateMethod($aggregateRoot))
        );

        $this->idStrategy->phpSpecIdGenerator($phpClass);
        $this->idStrategy->modifyPhpClass($phpClass);
        return $this->codeFileGenerator->generate($phpClass);
    }

    private function generateFileName(AggregateRoot $aggregateRoot): string
    {
        return str_replace(['./src/', '.php'], ['./spec/', 'Spec.php'], $aggregateRoot->exceptionPath());
    }

    private function generateBodyForCreateMethod(AggregateRoot $aggregateRoot): string
    {
        $body = '$this->beConstructedThrough(\'withId\', [$this->_id()]);' . "\n";
        $body .= '$this->getMessage()->shouldBe(\''.$aggregateRoot->className().' with ID id does not exists.\');';

        return $body;
    }
}
