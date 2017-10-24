<?php

namespace Pilsniak\ProophGen;

use Pilsniak\ProophGen\Model\Command;

interface CommandExecuter
{
    public function execute(Command $command): array;
}
