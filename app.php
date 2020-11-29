<?php

namespace App;

use App\Console\CommandRunner;
use App\Container\Container;

require_once 'vendor/autoload.php';
require_once 'config/config.php';
require_once 'container_setup.php';

/** @var Container $container */
/** @var CommandRunner $runner */

$runner = $container->get(CommandRunner::class);
$runner->run(array_slice($argv, 1));
