<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new \Pilsniak\Command\DoCommand());
// ... register commands

$application->run();