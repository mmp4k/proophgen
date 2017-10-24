<?php

namespace Pilsniak\ProophGen;

use Pilsniak\ProophGen\Model\ValueObject;

interface FileParser
{
    public function commands(): array;

    public function aggregateRoots(): array;

    /**
     * @return array|ValueObject[]
     */
    public function valueObjects(): array;
}
