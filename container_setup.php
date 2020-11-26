<?php

namespace App;

use App\ConsoleCommand\CdCommand;
use App\ConsoleCommand\ManageCommand;
use App\Service\AWSS3Connector;
use App\Container\Container;
use App\VirtualFileSystem\Backup;
use App\VirtualFileSystem\Backup\AWSS3;

$container = new Container();

$container->set(
    AWSS3Connector::class,
    null, [
        ['api_key' => 'aaaa']
    ]
);

$container->set(
    Backup::class,
    AWSS3::class, [
        AWSS3Connector::class
    ]
);

$container->set(
    ManageCommand::class,
    null,
    [],
    'command'
);

$container->set(
    CdCommand::class,
    null,
    [],
    'command'
);
