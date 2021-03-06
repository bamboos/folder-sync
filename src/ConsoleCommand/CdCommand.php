<?php

declare(strict_types = 1);


namespace App\ConsoleCommand;

use App\Console\Command;
use App\IO\Input;
use App\IO\Output;
use App\View\DirContentsView;
use App\VirtualFileSystem\FileSystem;

class CdCommand extends Command
{
    protected string $name = 'cd';

    private FileSystem $fileSystem;

    private DirContentsView $view;

    public function __construct(
        FileSystem $fileSystem,
        DirContentsView $view
    ) {
        $this->fileSystem = $fileSystem;
        $this->view = $view;

        parent::__construct();
    }

    public function execute(Input $input, Output $output): int
    {
        $dir = $this->getArgument('dir');

        if ($dir) {
            $this->fileSystem->changeDir($dir);
        }

        $this->view->display($this->fileSystem);

        return 0;
    }

    protected function configure(): void
    {
        $this->setArgument('dir');
    }
}
