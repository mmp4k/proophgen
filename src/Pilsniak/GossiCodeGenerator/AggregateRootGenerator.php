<?php

namespace Pilsniak\GossiCodeGenerator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpInterface;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootCodeGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootEventGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootEventGuardGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootEventSourcedRepository;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootExceptionNotFoundGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootInMemoryRepository;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootRepositoryInterfaceGenerator;
use Pilsniak\ProophGen\AggregateRootExecuter;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Pilsniak\ProophGen\Model\FileToSave;

class AggregateRootGenerator implements AggregateRootExecuter
{
    /**
     * @var AggregateRootCodeGenerator
     */
    private $aggregateRootCodeGenerator;
    /**
     * @var AggregateRootExceptionNotFoundGenerator
     */
    private $aggregateRootExceptionNotFoundGenerator;
    /**
     * @var AggregateRootEventGenerator
     */
    private $aggregateRootEventGenerator;
    /**
     * @var AggregateRootRepositoryInterfaceGenerator
     */
    private $aggregateRootRepositoryInterfaceGenerator;
    /**
     * @var AggregateRootInMemoryRepository
     */
    private $aggregateRootInMemoryRepository;
    /**
     * @var AggregateRootEventSourcedRepository
     */
    private $aggregateRootEventSourcedRepository;
    /**
     * @var AggregateRootEventGuardGenerator
     */
    private $aggregateRootEventGuardGenerator;

    /**
     * @param AggregateRootCodeGenerator $aggregateRootCodeGenerator
     * @param AggregateRootExceptionNotFoundGenerator $aggregateRootExceptionNotFoundGenerator
     * @param AggregateRootEventGenerator $aggregateRootEventGenerator
     * @param AggregateRootRepositoryInterfaceGenerator $aggregateRootRepositoryInterfaceGenerator
     * @param AggregateRootInMemoryRepository $aggregateRootInMemoryRepository
     * @param AggregateRootEventSourcedRepository $aggregateRootEventSourcedRepository
     * @param AggregateRootEventGuardGenerator $aggregateRootEventGuardGenerator
     */
    public function __construct(AggregateRootCodeGenerator $aggregateRootCodeGenerator,
                                AggregateRootExceptionNotFoundGenerator $aggregateRootExceptionNotFoundGenerator,
                                AggregateRootEventGenerator $aggregateRootEventGenerator,
                                AggregateRootRepositoryInterfaceGenerator $aggregateRootRepositoryInterfaceGenerator,
                                AggregateRootInMemoryRepository $aggregateRootInMemoryRepository,
                                AggregateRootEventSourcedRepository $aggregateRootEventSourcedRepository,
                                AggregateRootEventGuardGenerator $aggregateRootEventGuardGenerator)
    {
        $this->aggregateRootCodeGenerator = $aggregateRootCodeGenerator;
        $this->aggregateRootExceptionNotFoundGenerator = $aggregateRootExceptionNotFoundGenerator;
        $this->aggregateRootEventGenerator = $aggregateRootEventGenerator;
        $this->aggregateRootRepositoryInterfaceGenerator = $aggregateRootRepositoryInterfaceGenerator;
        $this->aggregateRootInMemoryRepository = $aggregateRootInMemoryRepository;
        $this->aggregateRootEventSourcedRepository = $aggregateRootEventSourcedRepository;
        $this->aggregateRootEventGuardGenerator = $aggregateRootEventGuardGenerator;
    }

    public function execute(AggregateRoot $aggregateRoot): array
    {
        $return = [
            $this->aggregateRootCodeGenerator->execute($aggregateRoot),
            $this->aggregateRootRepositoryInterfaceGenerator->execute($aggregateRoot),
            $this->aggregateRootExceptionNotFoundGenerator->execute($aggregateRoot)
        ];

        foreach ($aggregateRoot->events() as $event) {
            $return[] = $this->aggregateRootEventGenerator->execute($aggregateRoot, $event);
            $return[] = $this->aggregateRootEventGuardGenerator->execute($aggregateRoot, $event);
        }

        $return[] = $this->aggregateRootInMemoryRepository->execute($aggregateRoot);
        $return[] = $this->aggregateRootEventSourcedRepository->execute($aggregateRoot);

        return $return;
    }


}
