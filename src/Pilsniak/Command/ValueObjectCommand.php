<?php

namespace Pilsniak\Command;

use gossi\codegen\generator\CodeFileGenerator;
use League\Flysystem\Adapter\Local;
use Pilsniak\FlySystem\FileSystem;
use Pilsniak\GossiCodeGenerator\ValueObjectGenerator\PhpSpecValueObjectGenerator;
use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\Model\ValueObject;
use Pilsniak\ProophGen\ProophGenerator\ValueObjectGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ValueObjectCommand extends Command
{
    protected function configure()
    {
        $this->setName('value-object');
        $this->setDescription('Create value object');
        $this->setAliases(['vo']);
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

        $valueObject = new ValueObject(str_replace('/', '\\', $input->getArgument('name')));

        $valueObjectGenerator = new ValueObjectGenerator(
            new \Pilsniak\GossiCodeGenerator\ValueObjectGenerator(new \Pilsniak\GossiCodeGenerator\ValueObjectGenerator\ValueObjectGenerator($codeFileGenerator)),
            new PhpSpecValueObjectGenerator(new PhpSpecValueObjectGenerator\PhpSpecValueObjectGenerator($codeFileGenerator))
        );

        $filesToSave = $valueObjectGenerator->generate($valueObject);

        $fileSystem = new FileSystem(new \League\Flysystem\Filesystem(new Local('./')));

        $output->writeln('Creating files:');
        foreach ($filesToSave as $fileToSave) {
            $fileSystem->save($fileToSave->filename(), $fileToSave->fileContent());
            $output->writeln('[v] ' . $fileToSave->filename());
        }
    }


}