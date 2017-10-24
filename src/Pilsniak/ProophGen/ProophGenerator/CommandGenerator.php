<?php

namespace Pilsniak\ProophGen\ProophGenerator;

use Pilsniak\ProophGen\CommandExecuter;
use Pilsniak\ProophGen\Model\Command;

class CommandGenerator
{
    /**
     * @var CommandExecuter
     */
    private $commandExecuter;

    public function __construct(CommandExecuter $commandExecuter)
    {
        $this->commandExecuter = $commandExecuter;
    }

    public function generate(Command $command): array
    {
        return $this->commandExecuter->execute($command);
    }
}
