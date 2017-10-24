<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpConstant;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\FileToSave;

class AggregateRootEventSourcedRepository
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
        return new FileToSave('Infrastructure/'.$aggregateRoot->className().'/EventSourced.php', $this->content($aggregateRoot));
    }


    private function content(AggregateRoot $aggregateRoot): string
    {
        $phpClass = new PhpClass('Infrastructure\\'.$aggregateRoot->className().'\EventSourced');
        $phpClass->addUseStatement($aggregateRoot->repositoryInterfaceQualifiedName());
        $phpClass->addUseStatement($aggregateRoot->exceptionQualifiedName());
        $phpClass->addUseStatement($aggregateRoot->qualifiedName());
        $phpClass->addUseStatement('Prooph\EventSourcing\Aggregate\AggregateRepository');
        $phpClass->addInterface($aggregateRoot->repositoryInterfaceName());
        $phpClass->setProperty(PhpProperty::create('aggregateRepository'));

        $phpClass->setMethod(
            PhpMethod::create('__construct')
                ->addParameter(PhpParameter::create('aggregateRepository')->setType('AggregateRepository'))
                ->setBody('$this->aggregateRepository = $aggregateRepository;')
        );
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
                ->setBody('$this->aggregateRepository->saveAggregateRoot($'.$aggregateRoot->variableName().');')
        );

        return $this->codeFileGenerator->generate($phpClass);
    }

    private function bodyForGetMethod(AggregateRoot $aggregateRoot): string
    {
        $string = '$row = $this->aggregateRepository->getAggregateRoot($id);' . "\n";
        $string .= 'if (!$row) {' ."\n";
        $string .= "\t" . 'throw '.$aggregateRoot->className().'NotFound::withId($id);' . "\n";
        $string .= '}' . "\n";
        $string .= 'return $row;';

        return $string;
    }
}
