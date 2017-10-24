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

    public function variableName()
    {
        return strtolower($this->valueObject->className());
    }

    public function variable()
    {
        return '$'.$this->variableName();
    }

    public function property()
    {
        return '$this->'.$this->variableName();
    }
}
