<?php

namespace Pilsniak\ProophGen;

use Pilsniak\ProophGen\Model\ValueObject;

interface ValueObjectExecuter
{
    public function execute(ValueObject $valueObject): string;
}
