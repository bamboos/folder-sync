<?php

declare(strict_types = 1);


namespace App\ConsoleCommand;

use App\Console\Command;
use App\IO\Input;
use App\IO\Output;

include "folder_view.php";

class CdCommand extends Command
{
    protected string $name = 'cd';

    private array $current;

    private array $path;

    private array $folder = [
        'name' => 'Folder1',
        'contents' => [
            [
                'name' => 'Sub1'
            ],
            'Sub2' => [
                'name' => 'Sub2',
                'contents' => [
                    [
                        'name' => 'Sub21'
                    ],
                    [
                        'name' => 'Sub22',
                        'contents' => []
                    ],
                    [
                        'name' => 'Sub23'
                    ]
                ]
            ],
            [
                'name' => 'Sub3'
            ]
        ]
    ];

    public function execute(Input $input, Output $output): int
    {
        if (!isset($this->current)) {
            $this->current = &$this->folder;
        } else {
            $name = $this->getArgument('dir');

            if (is_null($name)) {
                return 0;
            }

            if ($name == '..' && !empty($this->path)) {
                $last = count($this->path) - 1;
                $this->current = &$this->path[$last];
                unset($this->path[$last]);
            } elseif (isset($this->current['contents'][$name])) {
                $this->path[] = &$this->current;
                $this->current = &$this->current['contents'][$name];
            } else {
                echo "No folder with the given name '{$name}'\n. Or it is empty.";
            }
        }

        folder_view($this->current, !empty($this->path));

        return 0;
    }

    protected function configure(): void
    {
        $this->setArgument('dir');
    }
}