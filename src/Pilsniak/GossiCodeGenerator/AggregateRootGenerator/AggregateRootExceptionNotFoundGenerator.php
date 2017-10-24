<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\FileToSave;

class AggregateRootExceptionNotFoundGenerator
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
        return new FileToSave($aggregateRoot->exceptionPath(), $this->getContentAggregateRootException($aggregateRoot));
    }

    private function getContentAggregateRootException(AggregateRoot $aggregateRoot)
    {
        $class = new PhpClass($aggregateRoot->exceptionQualifiedName());
        $class->setParentClassName('\DomainException');
        $class->setMethod(
            PhpMethod::create('withId')
                ->setStatic(true)
                ->addParameter(PhpParameter::create('id')->setType('string'))
                ->setType('self')
                ->setBody('return new self(sprintf("'.$aggregateRoot->className().' with ID %s does not exists.", $id));')
        );

        return $this->codeGenerator->generate($class);
    }
}
