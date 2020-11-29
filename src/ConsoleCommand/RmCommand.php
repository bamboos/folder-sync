<?php

declare(strict_types = 1);


namespace App\ConsoleCommand;

use App\Console\Command;
use App\IO\Input;
use App\IO\Output;
use App\View\DirContentsView;
use App\VirtualFileSystem\FileSystem;
use App\VirtualFileSystem\Persistence;

class RmCommand extends Command
{
    protected string $name = 'rm';

    private FileSystem $fileSystem;

    private DirContentsView $view;

    private Persistence $persistence;

    public function __construct(
        FileSystem $fileSystem,
        DirContentsView $view,
        Persistence $persistence
    ) {
        $this->fileSystem = $fileSystem;
        $this->view = $view;

        parent::__construct();
        $this->persistence = $persistence;
    }

    public function execute(Input $input, Output $output): int
    {
        $file = $this->getArgument('file');
        
        if ($file) {
            $this->fileSystem->rmFile($file);
            $this->persistence->store($this->fileSystem);
        }

        $this->view->display($this->fileSystem);

        return 0;
    }

    protected function configure(): void
    {
        $this->setArgument('file');
    }
}
