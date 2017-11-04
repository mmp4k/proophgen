<?php

namespace Pilsniak\Command;

use gossi\codegen\generator\CodeFileGenerator;
use League\Flysystem\Adapter\Local;
use Pilsniak\FlySystem\FileSystem;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootCodeGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootEventGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootEventGuardGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootEventSourcedRepository;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootExceptionNotFoundGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootInMemoryRepository;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\AggregateRootRepositoryInterfaceGenerator;
use Pilsniak\GossiCodeGenerator\AggregateRootGenerator\PhpSpecGenerator;
use Pilsniak\GossiCodeGenerator\CommandGenerator\PhpSpecCommandGenerator;
use Pilsniak\GossiCodeGenerator\IdStrategy\RamseyUuidIdStrategy;
use Pilsniak\GossiCodeGenerator\IdStrategy\StringIdStrategy;
use Pilsniak\GossiCodeGenerator\ValueObjectGenerator\PhpSpecValueObjectGenerator;
use Pilsniak\ProophGen\ProophGenerator;
use Pilsniak\ProophGen\ProophGenerator\AggregateRootGenerator;
use Pilsniak\ProophGen\ProophGenerator\CommandGenerator;
use Pilsniak\ProophGen\ProophGenerator\ValueObjectGenerator;
use Pilsniak\YamlFileParser\YamlLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DoCommand extends Command
{
    protected function configure()
    {
        $this->setName('do');
        $this->setDescription('Create project using prooph.yml');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $codeFileGenerator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);

        $loader = new YamlLoader(file_get_contents('prooph.yml'));

        $idStrategy = new StringIdStrategy();

        if ($loader->idPolicy()->name() === 'Ramsey\Uuid\UuidInterface') {
            $idStrategy = new RamseyUuidIdStrategy();
        }

        /* Command Generator */
        $commandHandlerCodeGenerator = new \Pilsniak\GossiCodeGenerator\CommandGenerator\CommandHandlerGenerator($codeFileGenerator);
        $commandCodeGenerator = new \Pilsniak\GossiCodeGenerator\CommandGenerator\CommandGenerator($codeFileGenerator, $idStrategy);
        $commandGenerator = new CommandGenerator(
            new \Pilsniak\GossiCodeGenerator\CommandGenerator($commandHandlerCodeGenerator, $commandCodeGenerator),
            new PhpSpecCommandGenerator(new PhpSpecCommandGenerator\PhpSpecCommandGenerator($codeFileGenerator, $idStrategy), new PhpSpecCommandGenerator\PhpSpecCommandHandlerGenerator($codeFileGenerator))
            );

        /* Value Object Generator */
        $valueObjectGenerator = new ValueObjectGenerator(
            new \Pilsniak\GossiCodeGenerator\ValueObjectGenerator(new \Pilsniak\GossiCodeGenerator\ValueObjectGenerator\ValueObjectGenerator($codeFileGenerator)),
            new PhpSpecValueObjectGenerator(new PhpSpecValueObjectGenerator\PhpSpecValueObjectGenerator($codeFileGenerator))
        );

        /* Aggregate Root Generator */
        $aggregateRootCodeGenerator = new AggregateRootCodeGenerator($codeFileGenerator, $idStrategy);
        $aggregateRootEventGenerator = new AggregateRootEventGenerator($codeFileGenerator, $idStrategy);
        $aggregateRootExceptionNotFoundGenerator = new AggregateRootExceptionNotFoundGenerator($codeFileGenerator, $idStrategy);
        $aggregateRootRepositoryInterfaceGenerator = new AggregateRootRepositoryInterfaceGenerator($codeFileGenerator, $idStrategy);
        $aggregateRootRepositoryInMemoryGenerator = new AggregateRootInMemoryRepository($codeFileGenerator, $idStrategy);
        $aggregateRootRepositoryEventSourcedGenerator = new AggregateRootEventSourcedRepository($codeFileGenerator, $idStrategy);
        $aggregateRootEventGuardGenerator = new AggregateRootEventGuardGenerator($codeFileGenerator);
        $rootGenerator = new AggregateRootGenerator(
            new \Pilsniak\GossiCodeGenerator\AggregateRootGenerator($aggregateRootCodeGenerator, $aggregateRootExceptionNotFoundGenerator, $aggregateRootEventGenerator, $aggregateRootRepositoryInterfaceGenerator, $aggregateRootRepositoryInMemoryGenerator, $aggregateRootRepositoryEventSourcedGenerator, $aggregateRootEventGuardGenerator),
            new PhpSpecGenerator(
                new PhpSpecGenerator\PhpSpecAggregateCode($codeFileGenerator, $idStrategy),
                new PhpSpecGenerator\PhpSpecEventSourced($codeFileGenerator, $idStrategy),
                new PhpSpecGenerator\PhpSpecEvent($codeFileGenerator, $idStrategy),
                new PhpSpecGenerator\PhpSpecExceptionNotFound($codeFileGenerator, $idStrategy),
                new PhpSpecGenerator\PhpSpecInMemoryRepository($codeFileGenerator, $idStrategy),
                new PhpSpecGenerator\PhpSpecEventGuard($codeFileGenerator)
            )
        );

        $proophGenerator = new ProophGenerator($commandGenerator, $valueObjectGenerator, $rootGenerator);
        $proophGenerator->generate($loader, new FileSystem(new \League\Flysystem\Filesystem(new Local('./'))));

    }
}