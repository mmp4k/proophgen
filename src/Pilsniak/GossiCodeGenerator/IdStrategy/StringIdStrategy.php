<?php

namespace Pilsniak\GossiCodeGenerator\IdStrategy;

use gossi\codegen\model\AbstractPhpStruct;
use gossi\codegen\model\PhpMethod;
use Pilsniak\ProophGen\IdStrategy;

class StringIdStrategy implements IdStrategy
{
    public function type(): string
    {
        return 'string';
    }

    public function convertToType(string $expression): string
    {
        return $expression;
    }

    public function convertToString(string $expression): string
    {
        return $expression;
    }

    public function modifyPhpClass(AbstractPhpStruct $class)
    {

    }

    public function phpSpecIdGenerator(AbstractPhpStruct $class)
    {
        $class->setMethod(
            PhpMethod::create('_id')
                ->setVisibility('protected')
                ->setBody('return \'id\';')
        );
    }
}
