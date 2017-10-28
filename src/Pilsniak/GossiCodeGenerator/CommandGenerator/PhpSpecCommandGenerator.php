<?php

namespace Pilsniak\GossiCodeGenerator\CommandGenerator;

use Pilsniak\GossiCodeGenerator\CommandGenerator\PhpSpecCommandGenerator\PhpSpecCommandHandlerGenerator;
use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\Model\FileToSave;
use Pilsniak\ProophGen\ProophGenerator\CommandGenerator\PhpSpecCommandExecuter;

class PhpSpecCommandGenerator implements PhpSpecCommandExecuter
{
    /**
     * @var PhpSpecCommandGenerator
     */
    private $phpSpecCommandGenerator;
    /**
     * @var PhpSpecCommandHandlerGenerator
     */
    private $phpSpecCommandHandlerGenerator;

    public function __construct(PhpSpecCommandGenerator\PhpSpecCommandGenerator $phpSpecCommandGenerator,
                                PhpSpecCommandHandlerGenerator $phpSpecCommandHandlerGenerator)
    {
        $this->phpSpecCommandGenerator = $phpSpecCommandGenerator;
        $this->phpSpecCommandHandlerGenerator = $phpSpecCommandHandlerGenerator;
    }

    /**
     * @param Command $command
     *
     * @return array|FileToSave[]
     */
    public function execute(Command $command): array
    {
        return [
            $this->phpSpecCommandGenerator->execute($command),
            $this->phpSpecCommandHandlerGenerator->execute($command),
        ];
    }
}
