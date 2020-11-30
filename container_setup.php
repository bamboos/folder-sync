<?php

namespace App;

use App\Console\CommandRunner;
use App\ConsoleCommand\AddFileCommand;
use App\ConsoleCommand\BackupCommand;
use App\ConsoleCommand\CdCommand;
use App\ConsoleCommand\HelpCommand;
use App\ConsoleCommand\ManageCommand;
use App\ConsoleCommand\MkDirCommand;
use App\ConsoleCommand\RmCommand;
use App\Http\Client;
use App\Logger\File;
use App\Logger\Logger;
use App\Service\AWS\AWSConnector;
use App\Service\AWS\Signature;
use App\Container\Container;
use App\Service\FileManager;
use App\View\DirContentsView;
use App\VirtualFileSystem\Backup;
use App\VirtualFileSystem\Backup\AWSS3;
use App\VirtualFileSystem\FileContent;
use App\VirtualFileSystem\FileSystem;
use App\VirtualFileSystem\Persistence;

/** @var array $config */

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
    null, [
        Persistence::class,
        FileSystem::class
    ],
    'command'
);

$container->set(
    DirContentsView::class
);

$container->set(
    FileSystem::class,
    null, [
        FileContent::class
    ]
);

$container->set(
    CdCommand::class,
    null, [
        FileSystem::class,
        DirContentsView::class
    ],
    'command'
);

$container->set(
    MkDirCommand::class,
    null,[
        FileSystem::class,
        DirContentsView::class,
        Persistence::class
    ],
    'command'
);

$container->set(
    FileManager::class
);

$container->set(
    Persistence::class,
    Persistence\File::class, [
        FileManager::class,
        'data/file_system.data'
    ]
);

$container->set(
    FileContent::class,
    FileContent\Local::class, [
        FileManager::class
    ]
);

$container->set(
    AddFileCommand::class,
    null,[
    FileSystem::class,
    DirContentsView::class,
    Persistence::class
],
    'command'
);

$container->set(
    RmCommand::class,
    null,[
    FileSystem::class,
    DirContentsView::class,
    Persistence::class
],
    'command'
);

$container->set(
    HelpCommand::class,
    null,
    [],
    'command'
);

$container->set(
    Signature::class,
    Signature\SignatureV4::class, [
        $config['aws']['connector']['signature']
    ]
);

$container->set(
    Logger::class,
    File::class, [
        'data/log.txt',
        FileManager::class
    ]
);

$container->set(
    Client::class,
    Client\Curl::class, [
        Logger::class
    ]
);

$container->set(
    AWSConnector::class,
    null, [
        $config['aws']['connector'],
        Signature::class,
        Client::class
    ]
);

$container->set(
    Backup::class,
    AWSS3::class, [
        AWSConnector::class
    ]
);

$container->set(
    BackupCommand::class,
    null, [
        Persistence::class,
        FileSystem::class,
        Backup::class
    ],
    'command'
);

$container->set(
    CommandRunner::class,
    null, [
        $container->getTagged('command')
    ]
);
