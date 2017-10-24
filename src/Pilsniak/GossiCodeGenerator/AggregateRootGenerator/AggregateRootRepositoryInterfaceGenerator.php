<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpInterface;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\FileToSave;

class AggregateRootRepositoryInterfaceGenerator
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
        return new FileToSave($aggregateRoot->repositoryInterfacePath(), $this->getContentAggregateRootRepository($aggregateRoot));
    }

    private function getContentAggregateRootRepository(AggregateRoot $aggregateRoot): string
    {
        $class = new PhpInterface();
        $class->setQualifiedName($aggregateRoot->repositoryInterfaceQualifiedName());
        $class->setMethod(
            PhpMethod::create('save')
                ->addParameter(
                    PhpParameter::create($aggregateRoot->variableName())->setType($aggregateRoot->className())
                )
        );
        $class->setMethod(
            PhpMethod::create('get')
                ->setType($aggregateRoot->className())
                ->addParameter(
                    PhpParameter::create('id')->setType('string')
                )
        );

        return $this->codeGenerator->generate($class);
    }

}
