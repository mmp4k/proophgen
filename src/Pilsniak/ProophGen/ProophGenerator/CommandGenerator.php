<?php

namespace Pilsniak\ProophGen\ProophGenerator;

use Pilsniak\ProophGen\CommandExecuter;
use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\ProophGenerator\CommandGenerator\PhpSpecCommandExecuter;

class CommandGenerator
{
    /**
     * @var CommandExecuter
     */
    private $commandExecuter;
    /**
     * @var PhpSpecCommandExecuter
     */
    private $phpSpecCommandExecuter;

    public function __construct(CommandExecuter $commandExecuter, PhpSpecCommandExecuter $phpSpecCommandExecuter)
    {
        $this->commandExecuter = $commandExecuter;
        $this->phpSpecCommandExecuter = $phpSpecCommandExecuter;
    }

    public function generate(Command $command): array
    {
        return array_merge(
            $this->commandExecuter->execute($command),
            $this->phpSpecCommandExecuter->execute($command)
        );
    }
}
