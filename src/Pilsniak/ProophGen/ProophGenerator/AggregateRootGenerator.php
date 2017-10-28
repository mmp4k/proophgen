<?php

namespace Pilsniak\ProophGen\ProophGenerator;

use Pilsniak\ProophGen\AggregateRootExecuter;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\ProophGenerator\AggregateRootGenerator\PhpSpecAggregateRootExecuter;

class AggregateRootGenerator
{
    /**
     * @var AggregateRootExecuter
     */
    private $aggregateRootExecuter;
    /**
     * @var PhpSpecAggregateRootExecuter
     */
    private $phpSpecAggregateRootExecuter;

    public function __construct(AggregateRootExecuter $aggregateRootExecuter, PhpSpecAggregateRootExecuter $phpSpecAggregateRootExecuter)
    {
        $this->aggregateRootExecuter = $aggregateRootExecuter;
        $this->phpSpecAggregateRootExecuter = $phpSpecAggregateRootExecuter;
    }

    /**
     * @param AggregateRoot $aggregateRoot
     *
     * @return array|FileToSave[]
     */
    public function generate(AggregateRoot $aggregateRoot): array
    {
        return array_merge(
            $this->aggregateRootExecuter->execute($aggregateRoot),
            $this->phpSpecAggregateRootExecuter->execute($aggregateRoot)
        );
    }
}
