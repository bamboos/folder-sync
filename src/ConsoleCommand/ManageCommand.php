<?php

declare(strict_types = 1);

namespace App\ConsoleCommand;

use App\Console\InteractiveCommand;
use App\IO\Input;
use App\IO\Output;
use App\VirtualFileSystem\FileSystem;
use App\VirtualFileSystem\Persistence;

class ManageCommand extends InteractiveCommand
{
    protected string $name = 'manage';

    private Persistence $persistence;

    private FileSystem $fileSystem;

    protected string $prompt = 'Command (use \'help\' for available): ';

    public function __construct(
        Persistence $persistence,
        FileSystem $fileSystem
    ) {
        $this->persistence = $persistence;
        $this->fileSystem = $fileSystem;

        parent::__construct();
    }

    public function execute(Input $input, Output $output): int
    {
        if ($root = $this->persistence->load()) {
            $this->fileSystem->setRoot($root);
        }

        $this->getRunner()->run(['cd', null]);

        return parent::execute($input, $output);
    }

    protected function configure(): void
    {
        $this->addCommand('cd');
        $this->addCommand('mkdir');
        $this->addCommand('file');
        $this->addCommand('rm');
        $this->addCommand('help');
    }
}
