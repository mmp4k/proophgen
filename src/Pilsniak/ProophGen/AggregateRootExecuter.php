<?php

namespace Pilsniak\ProophGen;

use Pilsniak\ProophGen\Model\AggregateRoot;

interface AggregateRootExecuter
{
    public function execute(AggregateRoot $aggregateRoot): array;
}
