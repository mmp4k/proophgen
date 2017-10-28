<?php

namespace Pilsniak\ProophGen\ProophGenerator;

use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\ProophGen\ValueObjectExecuter;

class ValueObjectGenerator
{
    /**
     * @var ValueObjectExecuter
     */
    private $valueObjectExecuter;

    public function __construct(ValueObjectExecuter $valueObjectExecuter)
    {
        $this->valueObjectExecuter = $valueObjectExecuter;
    }

    /**
     * @param ValueObject $valueObject
     *
     * @return array|FileToSave[]
     */
    public function generate(ValueObject $valueObject)
    {
        return [
            new FileToSave('./src/'.$valueObject->path(), $this->valueObjectExecuter->execute($valueObject))
        ];

    }
}
