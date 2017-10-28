<?php

namespace Pilsniak\GossiCodeGenerator\ValueObjectGenerator\PhpSpecValueObjectGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use phootwork\file\File;
use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\Model\ValueObject;

class PhpSpecValueObjectGenerator
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
//        var_dump($this->generateContent($valueObject));exit;

        return new FileToSave($this->generateName($valueObject), $this->generateContent($valueObject));
    }

    private function generateContent(ValueObject $valueObject): string
    {
        $phpClass = new PhpClass();
        $phpClass->setQualifiedName('spec\\'.$valueObject->qualifiedName().'Spec');
        $phpClass->setParentClassName('ObjectBehavior');
        $phpClass->addUseStatement('PhpSpec\ObjectBehavior');
        $phpClass->addUseStatement('Prophecy\Argument');
        $phpClass->addUseStatement($valueObject->qualifiedName());

        $phpClass->setMethod(
            PhpMethod::create('let')
                ->setBody('$this->beConstructedThrough(\'create\', [\'something\']);')
        );

        $phpClass->setMethod(
            PhpMethod::create('it_is_comparable')
                ->setBody($this->generateBodyForComparableMethod($valueObject))
        );

        $phpClass->setMethod(
            PhpMethod::create('it_is_available_as_primitive')
                ->setBody('$this->get()->shouldBe(\'something\');')
        );

        return $this->codeFileGenerator->generate($phpClass);
    }

    private function generateName(ValueObject $valueObject): string
    {
        $prefix = './spec/' . $valueObject->path();

        return str_replace('.php', 'Spec.php', $prefix);
    }

    private function generateBodyForComparableMethod(ValueObject $valueObject): string
    {
        $body = '$other'.$valueObject->className().' = '.$valueObject->className().'::create(\'something\');' . "\n\n";

        $body .= '$this->isEqual($other'.$valueObject->className().')->shouldBe(true);';

        return $body;
    }
}
