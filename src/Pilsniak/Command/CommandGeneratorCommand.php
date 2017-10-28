<?php

namespace Pilsniak\Command;

use gossi\codegen\generator\CodeFileGenerator;
use League\Flysystem\Adapter\Local;
use Pilsniak\FlySystem\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pilsniak\GossiCodeGenerator\CommandGenerator\PhpSpecCommandGenerator;
use Pilsniak\ProophGen\ProophGenerator\CommandGenerator;

class CommandGeneratorCommand extends Command
{
    protected function configure()
    {
        $this->setName('command');
        $this->setDescription('Create command and command handler');
        $this->setAliases(['c']);
        $this->addArgument('name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $codeFileGenerator = new CodeFileGenerator([
            'generateDocblock' => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'declareStrictTypes' => true
        ]);

        $command = new \Pilsniak\ProophGen\Model\Command(str_replace('/', '\\', $input->getArgument('name')));

        $commandHandlerCodeGenerator = new \Pilsniak\GossiCodeGenerator\CommandGenerator\CommandHandlerGenerator($codeFileGenerator);
        $commandCodeGenerator = new \Pilsniak\GossiCodeGenerator\CommandGenerator\CommandGenerator($codeFileGenerator);
        $commandGenerator = new CommandGenerator(
            new \Pilsniak\GossiCodeGenerator\CommandGenerator($commandHandlerCodeGenerator, $commandCodeGenerator),
            new PhpSpecCommandGenerator(new PhpSpecCommandGenerator\PhpSpecCommandGenerator($codeFileGenerator), new PhpSpecCommandGenerator\PhpSpecCommandHandlerGenerator($codeFileGenerator))
        );

        $filesToSave = $commandGenerator->generate($command);

        $fileSystem = new FileSystem(new \League\Flysystem\Filesystem(new Local('./')));

        $output->writeln('Creating files:');
        foreach ($filesToSave as $fileToSave) {
            $fileSystem->save($fileToSave->filename(), $fileToSave->fileContent());
            $output->writeln('[v] ' . $fileToSave->filename());
        }
    }


}