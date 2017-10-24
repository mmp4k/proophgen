<?php

namespace Pilsniak\ProophGen;

use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\ProophGenerator\AggregateRootGenerator;
use Pilsniak\ProophGen\ProophGenerator\CommandGenerator;
use Pilsniak\ProophGen\ProophGenerator\ValueObjectGenerator;

class ProophGenerator
{
    /**
     * @var CommandGenerator
     */
    private $commandGenerator;
    /**
     * @var ValueObjectGenerator
     */
    private $valueObjectGenerator;
    /**
     * @var AggregateRootGenerator
     */
    private $aggregateRootGenerator;

    public function __construct(CommandGenerator $commandGenerator, ValueObjectGenerator $valueObjectGenerator, AggregateRootGenerator $aggregateRootGenerator)
    {
        $this->commandGenerator = $commandGenerator;
        $this->valueObjectGenerator = $valueObjectGenerator;
        $this->aggregateRootGenerator = $aggregateRootGenerator;
    }

    public function generate(FileParser $fileParser, FileSystem $fileSystem)
    {
        /** @var FileToSave[] $toSave */
        $toSave = [];
        foreach ($fileParser->valueObjects() as $valueObject) {
            $toSave = array_merge($toSave, $this->valueObjectGenerator->generate($valueObject));
        }

        foreach ($fileParser->commands() as $command) {
            $toSave = array_merge($toSave, $this->commandGenerator->generate($command));
        }

        foreach ($fileParser->aggregateRoots() as $aggregateRoot) {
            $toSave = array_merge($toSave, $this->aggregateRootGenerator->generate($aggregateRoot));
        }

        foreach ($toSave as $fileToSave) {
            $fileSystem->save($fileToSave->filename(), $fileToSave->fileContent());
        }
    }
}
