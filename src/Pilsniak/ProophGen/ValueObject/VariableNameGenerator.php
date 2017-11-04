<?php

namespace Pilsniak\ProophGen\ValueObject;

use Pilsniak\ProophGen\Model\ValueObject;

class VariableNameGenerator
{
    /**
     * @var ValueObject
     */
    private $valueObject;

    public function __construct(ValueObject $valueObject)
    {
        $this->valueObject = $valueObject;
    }

    public function variableName(): string
    {
        return strtolower($this->valueObject->className()[0]).substr($this->valueObject->className(), 1);
    }

    public function variable(): string
    {
        return '$'.$this->variableName();
    }

    public function property(): string
    {
        return '$this->'.$this->variableName();
    }
}
