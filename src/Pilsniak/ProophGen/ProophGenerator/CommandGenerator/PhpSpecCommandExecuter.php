<?php

namespace Pilsniak\ProophGen\ProophGenerator\CommandGenerator;

use Pilsniak\ProophGen\Model\Command;
use Pilsniak\ProophGen\Model\FileToSave;

interface PhpSpecCommandExecuter
{
    /**
     * @param Command $command
     *
     * @return array|FileToSave[]
     */
    public function execute(Command $command): array;
}
