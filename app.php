<?php

namespace App;

require_once "vendor/autoload.php";
require_once "container_setup.php";

use App\Console\CommandRunner;

$runner = new CommandRunner($container->getTagged('command'));

$runner->run(array_slice($argv, 1));
