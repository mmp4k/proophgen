<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use Pilsniak\ProophGen\IdStrategy;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\FileToSave;

class PhpSpecInMemoryRepository
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
        $phpClass->setQualifiedName('spec\\Infrastructure\\'.$aggregateRoot->className().'\\InMemorySpec');
        $phpClass->setParentClassName('ObjectBehavior');
        $phpClass->addUseStatement('PhpSpec\ObjectBehavior');
        $phpClass->addUseStatement('Prophecy\Argument');
        $phpClass->addUseStatement('Prooph\Common\Messaging\Command');
        $phpClass->addUseStatement('Prooph\Common\Messaging\PayloadConstructable');
        $phpClass->addUseStatement('Infrastructure\\'.$aggregateRoot->className().'\\InMemory');
        $phpClass->addUseStatement($aggregateRoot->repositoryInterfaceQualifiedName());
        $phpClass->addUseStatement($aggregateRoot->exceptionQualifiedName());
        $phpClass->addUseStatement($aggregateRoot->qualifiedName());

        $phpClass->setMethod(
            PhpMethod::create('let')
                ->setBody('$this->shouldImplement('.$aggregateRoot->repositoryInterfaceName().'::class);')
        );
        $phpClass->setMethod(
            PhpMethod::create('it_can_save_'.$aggregateRoot->variableName())
                ->addParameter(
                    PhpParameter::create($aggregateRoot->variableName())
                        ->setType($aggregateRoot->className())
                )
                ->setBody($this->generateBodyForSaveMethod($aggregateRoot))
        );
        $phpClass->setMethod(
            PhpMethod::create('it_can_get_'.$aggregateRoot->variableName())
                ->addParameter(
                    PhpParameter::create($aggregateRoot->variableName())
                        ->setType($aggregateRoot->className())
                )
                ->setBody($this->generateBodyForGetMethod($aggregateRoot))
        );
        $phpClass->setMethod(
            PhpMethod::create('it_throw_exception_if_can_not_get_'.$aggregateRoot->variableName())
                ->setBody('$this->shouldThrow('.$aggregateRoot->exceptionClassName().'::class)->during(\'get\', [$this->_id()]);')
        );

        $this->idStrategy->modifyPhpClass($phpClass);
        $this->idStrategy->phpSpecIdGenerator($phpClass);
        return $this->codeFileGenerator->generate($phpClass);
    }

    private function generateFileName(AggregateRoot $aggregateRoot): string
    {
        return './spec/Infrastructure/'.$aggregateRoot->className().'/InMemorySpec.php';
    }

    private function generateBodyForGetMethod(AggregateRoot $aggregateRoot): string
    {
        $body = '$this->getWrappedObject()->data[\''.$this->idStrategy->value().'\'] = $'.$aggregateRoot->variableName().'->getWrappedObject();' . "\n";
        $body .= '$this->get($this->_id());';

        return $body;
    }

    private function generateBodyForSaveMethod(AggregateRoot $aggregateRoot): string
    {
        $body = '$'.$aggregateRoot->variableName().'->id()->willReturn($this->_id());' . "\n";
        $body .= '$this->save($'.$aggregateRoot->variableName().');';

        return $body;
    }
}
