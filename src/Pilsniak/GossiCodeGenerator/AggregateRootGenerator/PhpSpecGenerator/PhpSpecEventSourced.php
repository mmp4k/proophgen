<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\FileToSave;

class PhpSpecEventSourced
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
        $phpClass->setQualifiedName('spec\\Infrastructure\\'.$aggregateRoot->className().'\\EventSourcedSpec');
        $phpClass->setParentClassName('ObjectBehavior');
        $phpClass->addUseStatement('PhpSpec\ObjectBehavior');
        $phpClass->addUseStatement('Prophecy\Argument');
        $phpClass->addUseStatement('Infrastructure\\'.$aggregateRoot->className().'\\EventSourced');
        $phpClass->addUseStatement($aggregateRoot->repositoryInterfaceQualifiedName());
        $phpClass->addUseStatement($aggregateRoot->exceptionQualifiedName());
        $phpClass->addUseStatement($aggregateRoot->qualifiedName());
        $phpClass->addUseStatement('Prooph\EventSourcing\Aggregate\AggregateRepository');

        $phpClass->setMethod(
            PhpMethod::create('let')
                ->addParameter(
                    PhpParameter::create('repository')
                        ->setType('AggregateRepository')
                )
                ->setBody($this->generateBodyForLetMethod($aggregateRoot))
        );

        $phpClass->setMethod(
            PhpMethod::create('it_can_save_'.$aggregateRoot->variableName())
                ->addParameter(
                    PhpParameter::create('repository')
                        ->setType('AggregateRepository')
                )
                ->addParameter(
                    PhpParameter::create($aggregateRoot->variableName())
                        ->setType($aggregateRoot->className())
                )
                ->setBody($this->generateBodyForSaveMethod($aggregateRoot))
        );

        $phpClass->setMethod(
            PhpMethod::create('it_can_get_'.$aggregateRoot->variableName())
                ->addParameter(
                    PhpParameter::create('repository')
                        ->setType('AggregateRepository')
                )
                ->addParameter(
                    PhpParameter::create($aggregateRoot->variableName())
                        ->setType($aggregateRoot->className())
                )
                ->setBody($this->generateBodyForGetMethod($aggregateRoot))
        );

        $phpClass->setMethod(
            PhpMethod::create('it_throw_exception_if_can_not_get_'.$aggregateRoot->variableName())
                ->addParameter(
                    PhpParameter::create('repository')
                        ->setType('AggregateRepository')
                )
                ->setBody($this->generateBodyForGetExceptionMethod($aggregateRoot))
        );

        $this->idStrategy->modifyPhpClass($phpClass);
        $this->idStrategy->phpSpecIdGenerator($phpClass);
        return $this->codeFileGenerator->generate($phpClass);
    }

    private function generateFileName(AggregateRoot $aggregateRoot): string
    {
        return './spec/Infrastructure/'.$aggregateRoot->className().'/EventSourcedSpec.php';
    }

    private function generateBodyForLetMethod(AggregateRoot $aggregateRoot): string
    {
        $body = '$this->beConstructedWith($repository);' . "\n";
        $body .= '$this->shouldImplement('.$aggregateRoot->repositoryInterfaceName().'::class);';

        return $body;
    }

    private function generateBodyForSaveMethod(AggregateRoot $aggregateRoot): string
    {
        $body = '$repository->saveAggregateRoot($'.$aggregateRoot->variableName().')->shouldBeCalled();' . "\n";
        $body .= '$this->save($'.$aggregateRoot->variableName().');';

        return $body;
    }

    private function generateBodyForGetMethod(AggregateRoot $aggregateRoot): string
    {
        $body = '$repository->getAggregateRoot(\''.$this->idStrategy->value().'\')->shouldBeCalled();' . "\n";
        $body .= '$repository->getAggregateRoot(\''.$this->idStrategy->value().'\')->willReturn($'.$aggregateRoot->variableName().');' . "\n";
        $body .= '$this->get($this->_id())->shouldBe($'.$aggregateRoot->variableName().');' . "\n";

        return $body;
    }

    private function generateBodyForGetExceptionMethod(AggregateRoot $aggregateRoot): string
    {
        $body = '$repository->getAggregateRoot(\''.$this->idStrategy->value().'\')->shouldBeCalled();' . "\n";
        $body .= '$repository->getAggregateRoot(\''.$this->idStrategy->value().'\')->willReturn(null);' . "\n";
        $body .= '$this->shouldThrow('.$aggregateRoot->exceptionClassName().'::class)->during(\'get\', [$this->_id()]);' . "\n";

        return $body;
    }
}
