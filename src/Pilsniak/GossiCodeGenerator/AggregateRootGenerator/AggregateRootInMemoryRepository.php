<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpConstant;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use phootwork\file\File;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\FileToSave;

class AggregateRootInMemoryRepository
{
    /**
     * @var CodeFileGenerator
     */
    private $codeFileGenerator;

    public function __construct(CodeFileGenerator $codeFileGenerator)
    {
        $this->codeFileGenerator = $codeFileGenerator;
    }

    public function execute(AggregateRoot $aggregateRoot) : FileToSave
    {
        return new FileToSave('Infrastructure/'.$aggregateRoot->className().'/InMemory.php', $this->content($aggregateRoot));
    }

    private function content(AggregateRoot $aggregateRoot): string
    {
        $phpClass = new PhpClass('Infrastructure\\'.$aggregateRoot->className().'\InMemory');
        $phpClass->addUseStatement($aggregateRoot->repositoryInterfaceQualifiedName());
        $phpClass->addUseStatement($aggregateRoot->exceptionQualifiedName());
        $phpClass->addUseStatement($aggregateRoot->qualifiedName());
        $phpClass->addInterface($aggregateRoot->repositoryInterfaceName());
        $phpClass->setProperty(PhpProperty::create('data')->setType('array')->setValue(PhpConstant::create('[]')));
        $phpClass->setMethod(
            PhpMethod::create('get')
                ->addParameter(PhpParameter::create('id')->setType('string'))
                ->setType($aggregateRoot->className())
                ->setBody($this->bodyForGetMethod($aggregateRoot))
        );
        $phpClass->setMethod(
            PhpMethod::create('save')
                ->addParameter(PhpParameter::create($aggregateRoot->variableName())->setType($aggregateRoot->className()))
                ->setType('void')
                ->setBody('$this->data[$'.$aggregateRoot->variableName().'->id()] = $'.$aggregateRoot->variableName().';')
        );

        return $this->codeFileGenerator->generate($phpClass);
    }

    private function bodyForGetMethod(AggregateRoot $aggregateRoot)
    {
        $string = 'if (!isset($this->data[$id])) {' ."\n";
        $string .= "\t" . 'throw '.$aggregateRoot->className().'NotFound::withId($id);' . "\n";
        $string .= '}' . "\n";
        $string .= 'return $this->data[$id];';
        return $string;
    }
}
