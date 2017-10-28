<?php

namespace Pilsniak\GossiCodeGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\ProophGen\ValueObject\VariableNameGenerator;
use Pilsniak\ProophGen\ValueObjectExecuter;

class ValueObjectGenerator implements ValueObjectExecuter
{
    /**
     * @var ValueObjectGenerator\ValueObjectGenerator
     */
    private $valueObjectGenerator;

    public function __construct(ValueObjectGenerator\ValueObjectGenerator $valueObjectGenerator)
    {
        $this->valueObjectGenerator = $valueObjectGenerator;
    }

    public function execute(ValueObject $valueObject): array
    {
        return [
            $this->valueObjectGenerator->execute($valueObject)
        ];
    }
}
