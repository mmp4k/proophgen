<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\FileToSave;

class AggregateRootExceptionNotFoundGenerator
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

    public function execute(AggregateRoot $aggregateRoot): FileToSave
    {
        return new FileToSave($aggregateRoot->exceptionPath(), $this->getContentAggregateRootException($aggregateRoot));
    }

    private function getContentAggregateRootException(AggregateRoot $aggregateRoot)
    {
        $class = new PhpClass($aggregateRoot->exceptionQualifiedName());
        $class->setParentClassName('\DomainException');
        $class->setMethod(
            PhpMethod::create('withId')
                ->setStatic(true)
                ->addParameter(PhpParameter::create('id')->setType($this->idStrategy->type()))
                ->setType('self')
                ->setBody('return new self(sprintf("'.$aggregateRoot->className().' with ID %s does not exists.", '.$this->idStrategy->convertToString('$id').'));')
        );

        $this->idStrategy->modifyPhpClass($class);
        return $this->codeGenerator->generate($class);
    }
}
