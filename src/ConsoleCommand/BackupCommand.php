<?php

declare(strict_types = 1);

namespace App\ConsoleCommand;

use App\Console\Command;
use App\IO\Input;
use App\IO\Output;
use App\VirtualFileSystem\Backup;
use App\VirtualFileSystem\FileSystem;
use App\VirtualFileSystem\Persistence;

class BackupCommand extends Command
{
    protected string $name = 'backup';

    private Persistence $persistence;

    private FileSystem $fileSystem;

    private Backup $backup;

    public function __construct(
        Persistence $persistence,
        FileSystem $fileSystem,
        Backup $backup
    ) {
        $this->persistence = $persistence;
        $this->fileSystem = $fileSystem;

        parent::__construct();
        $this->backup = $backup;
    }

    public function execute(Input $input, Output $output): int
    {
        if ($root = $this->persistence->load()) {
            $this->fileSystem->setRoot($root);
            $this->backup->store($this->fileSystem);
        }

        return 0;
    }

    protected function configure(): void
    {

    }
}
