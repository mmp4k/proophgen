<?php

namespace Pilsniak\GossiCodeGenerator\AggregateRootGenerator;

use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecAggregateCode;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecEvent;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecEventSourced;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecExceptionNotFound;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator\PhpSpecInMemoryRepository;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\ProophGenerator\AggregateRootGenerator\PhpSpecAggregateRootExecuter;

class PhpSpecGenerator implements PhpSpecAggregateRootExecuter
{
    /**
     * @var PhpSpecAggregateCode
     */
    private $phpSpecAggregateCode;
    /**
     * @var PhpSpecEventSourced
     */
    private $phpSpecEventSourced;
    /**
     * @var PhpSpecEvent
     */
    private $phpSpecEvent;
    /**
     * @var PhpSpecExceptionNotFound
     */
    private $phpSpecExceptionNotFound;
    /**
     * @var PhpSpecInMemoryRepository
     */
    private $phpSpecInMemoryRepository;

    public function __construct(
        PhpSpecAggregateCode $phpSpecAggregateCode,
        PhpSpecEventSourced $phpSpecEventSourced,
        PhpSpecEvent $phpSpecEvent,
        PhpSpecExceptionNotFound $phpSpecExceptionNotFound,
        PhpSpecInMemoryRepository $phpSpecInMemoryRepository
    )
    {
        $this->phpSpecAggregateCode = $phpSpecAggregateCode;
        $this->phpSpecEventSourced = $phpSpecEventSourced;
        $this->phpSpecEvent = $phpSpecEvent;
        $this->phpSpecExceptionNotFound = $phpSpecExceptionNotFound;
        $this->phpSpecInMemoryRepository = $phpSpecInMemoryRepository;
    }

    /**
     * @param AggregateRoot $aggregateRoot
     *
     * @return array|FileToSave[]
     */
    public function execute(AggregateRoot $aggregateRoot): array
    {
        $return = [
            $this->phpSpecAggregateCode->execute($aggregateRoot),
            $this->phpSpecEventSourced->execute($aggregateRoot),
            $this->phpSpecExceptionNotFound->execute($aggregateRoot),
            $this->phpSpecInMemoryRepository->execute($aggregateRoot)
        ];

        foreach ($aggregateRoot->events() as $event) {
            $return[] = $this->phpSpecEvent->execute($aggregateRoot, $event);
        }

        return $return;
    }
}
