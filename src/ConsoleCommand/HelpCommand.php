<?php

declare(strict_types = 1);


namespace App\ConsoleCommand;

use App\Console\Command;
use App\IO\Input;
use App\IO\Output;
use App\View\DirContentsView;
use App\VirtualFileSystem\FileSystem;
use App\VirtualFileSystem\Persistence;

class HelpCommand extends Command
{
    protected string $name = 'help';

    public function execute(Input $input, Output $output): int
    {
        $output->writeLine("'cd <name>' - enter folder");
        $output->writeLine("'file <path_to_file>' - create file");
        $output->writeLine("'mkdir <name>' - create folder");
        $output->writeLine("'rm <name>' - remove file/folder");

        return 0;
    }

    protected function configure(): void
    {
        //
    }
}
