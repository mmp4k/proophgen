<?php

namespace Pilsniak\ProophGen\ProophGenerator;

use Pilsniak\ProophGen\AggregateRootExecuter;
use Pilsniak\ProophGen\Model\AggregateRoot;

class AggregateRootGenerator
{
    /**
     * @var AggregateRootExecuter
     */
    private $aggregateRootExecuter;

    public function __construct(AggregateRootExecuter $aggregateRootExecuter)
    {
        $this->aggregateRootExecuter = $aggregateRootExecuter;
    }

    public function generate(AggregateRoot $aggregateRoot): array
    {
        return $this->aggregateRootExecuter->execute($aggregateRoot);
    }
}
