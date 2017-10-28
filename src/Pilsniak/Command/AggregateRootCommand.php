<?php

namespace Pilsniak\Command;

use gossi\codegen\generator\CodeFileGenerator;
use League\Flysystem\Adapter\Local;
use Pilsniak\FlySystem\FileSystem;
use Pilsniak\ProophGen\Model\AggregateRoot;
use Pilsniak\ProophGen\Model\Event;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootCodeGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootEventGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootEventSourcedRepository;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootExceptionNotFoundGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootInMemoryRepository;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootRepositoryInterfaceGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;
use Pilsniak\ProophGen\ProophGenerator\AggregateRootGenerator;

class AggregateRootCommand extends Command
{
    protected function configure()
    {
        $this->setName('aggregate-root');
        $this->setDescription('Create aggregate root');
        $this->setAliases(['ar']);
        $this->addArgument('name', InputArgument::REQUIRED);
        $this->addArgument('events', InputArgument::IS_ARRAY);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $events = [];
        foreach ($input->getArgument('events') as $event) {
            $isCreator = $event{0} === '!' ? true : false;
            if ($isCreator) {
                $event = substr($event, 1);
            }
            $events[] = new Event(str_replace('/', '\\',$event), $isCreator);
        }

        $aggregateRoot = new AggregateRoot(str_replace('/', '\\', $input->getArgument('name')), $events);

        $codeFileGenerator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);

        $aggregateRootCodeGenerator = new AggregateRootCodeGenerator($codeFileGenerator);
        $aggregateRootEventGenerator = new AggregateRootEventGenerator($codeFileGenerator);
        $aggregateRootExceptionNotFoundGenerator = new AggregateRootExceptionNotFoundGenerator($codeFileGenerator);
        $aggregateRootRepositoryInterfaceGenerator = new AggregateRootRepositoryInterfaceGenerator($codeFileGenerator);
        $aggregateRootRepositoryInMemoryGenerator = new AggregateRootInMemoryRepository($codeFileGenerator);
        $aggregateRootRepositoryEventSourcedGenerator = new AggregateRootEventSourcedRepository($codeFileGenerator);
        $rootGenerator = new AggregateRootGenerator(
            new \Pilsniak\GossiCodeGenerator\AggregateRootGenerator($aggregateRootCodeGenerator, $aggregateRootExceptionNotFoundGenerator, $aggregateRootEventGenerator, $aggregateRootRepositoryInterfaceGenerator, $aggregateRootRepositoryInMemoryGenerator, $aggregateRootRepositoryEventSourcedGenerator),
            new PhpSpecGenerator(
                new PhpSpecGenerator\PhpSpecAggregateCode($codeFileGenerator),
                new PhpSpecGenerator\PhpSpecEventSourced($codeFileGenerator),
                new PhpSpecGenerator\PhpSpecEvent($codeFileGenerator),
                new PhpSpecGenerator\PhpSpecExceptionNotFound($codeFileGenerator),
                new PhpSpecGenerator\PhpSpecInMemoryRepository($codeFileGenerator)
            )
        );

        $filesToSave = $rootGenerator->generate($aggregateRoot);

        $fileSystem = new FileSystem(new \League\Flysystem\Filesystem(new Local('./')));

        $output->writeln('Creating files:');
        foreach ($filesToSave as $fileToSave) {
            $fileSystem->save($fileToSave->filename(), $fileToSave->fileContent());
            $output->writeln('[v] ' . $fileToSave->filename());
        }
    }


}