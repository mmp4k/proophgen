<?php

namespace Pilsniak\ProophGen\ProophGenerator\AggregateRootGenerator;

use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\FileToSave;

interface PhpSpecAggregateRootExecuter
{
    /**
     * @param AggregateRoot $aggregateRoot
     *
     * @return array|FileToSave[]
     */
    public function execute(AggregateRoot $aggregateRoot): array;
}
