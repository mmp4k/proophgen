<?php

namespace Pilsniak\GossiCodeGenerator\ValueObjectGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\ProophGen\ValueObject\VariableNameGenerator;

class ValueObjectGenerator
{
    /**
     * @var CodeFileGenerator
     */
    private $codeFileGenerator;

    public function __construct(CodeFileGenerator $codeFileGenerator)
    {
        $this->codeFileGenerator = $codeFileGenerator;
    }

    public function execute(ValueObject $valueObject): FileToSave
    {
        return new FileToSave('./src/'.$valueObject->path(), $this->generateCode($valueObject));
    }

    protected function generateCode(ValueObject $valueObject): string
    {
        $property = new VariableNameGenerator($valueObject);

        $construct = PhpMethod::create('__construct')
            ->addParameter(PhpParameter::create($property->variableName())->setType('string'))
            ->setBody($property->property().' = '.$property->variable().';')
            ->setVisibility('private');

        $create = PhpMethod::create('create')
            ->addParameter(PhpParameter::create($property->variableName())->setType('string'))
            ->setBody('return new self('.$property->variable().');')
            ->setType('self')
            ->setStatic(true)
        ;

        $equal = PhpMethod::create('isEqual')
            ->addParameter(PhpParameter::create($property->variableName())->setType('self'))
            ->setBody('return '.$property->variable().'->get() === $this->get();')
            ->setType('bool')
        ;

        $get = PhpMethod::create('get')
            ->setType('string')
            ->setBody('return '.$property->property().';')
        ;

        $class = new PhpClass();
        $class->setQualifiedName($valueObject->qualifiedName());
        $class->setProperty(PhpProperty::create($property->variableName())->setType('string'));
        $class->setMethod($construct);
        $class->setMethod($create);
        $class->setMethod($equal);
        $class->setMethod($get);

        return $this->codeFileGenerator->generate($class);
    }

}
