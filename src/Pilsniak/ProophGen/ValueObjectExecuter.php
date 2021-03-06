<?php

namespace Pilsniak\ProophGen;

use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\Model\ValueObject;

interface ValueObjectExecuter
{
    /**
     * @param ValueObject $valueObject
     *
     * @return array|FileToSave[]
     */
    public function execute(ValueObject $valueObject): array;
}
