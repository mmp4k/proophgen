<?php

namespace Pilsniak\ProophGen\ProophGenerator\ValueObjectGenerator;

use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\Model\ValueObject;

interface PhpSpecValueObjectExecuter
{
    /**
     * @param ValueObject $valueObject
     *
     * @return array|FileToSave[]
     */
    public function execute(ValueObject $valueObject): array;
}
