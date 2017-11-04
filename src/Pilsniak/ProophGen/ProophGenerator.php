<?php

namespace Pilsniak\ProophGen;

use Pilsniak\ComposerGenerator\ComposerJsonGenerator;
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
    /**
     * @var ComposerJsonGenerator
     */
    private $composerJsonGenerator;

    public function __construct(CommandGenerator $commandGenerator, ValueObjectGenerator $valueObjectGenerator, AggregateRootGenerator $aggregateRootGenerator, ComposerJsonGenerator $composerJsonGenerator)
    {
        $this->commandGenerator = $commandGenerator;
        $this->valueObjectGenerator = $valueObjectGenerator;
        $this->aggregateRootGenerator = $aggregateRootGenerator;
        $this->composerJsonGenerator = $composerJsonGenerator;
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

        $toSave[] = $this->composerJsonGenerator->generate();

        foreach ($toSave as $fileToSave) {
            $fileSystem->save($fileToSave->filename(), $fileToSave->fileContent());
        }
    }
}
