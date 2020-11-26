<?php

declare(strict_types = 1);

namespace App\Console;

class CommandRunner
{
    private $commands;

    public function __construct(array $commands)
    {
        $this->registerCommands($commands);
    }

    public function run(array $args)
    {
        $name = $args[1];
        $command = $this->commands[$name];

        $refObj = new \ReflectionObject($command);
        $prop = $refObj->getProperty('arguments');
        $prop->setAccessible(true);
        $arguments = $prop->getValue($command);
        $prop->setValue($command, array_combine(
            array_keys($arguments),
            array_slice($args, 2)
        ));
        $prop->setAccessible(false);

        $command->execute();
    }

    /**
     * @param Command[] $commands
     */
    private function registerCommands(array $commands)
    {
        foreach ($commands as $command) {
            $refObj = new \ReflectionObject($command);
            $prop = $refObj->getProperty('name');
            $prop->setAccessible(true);
            $name = $prop->getValue($command);
            $prop->setAccessible(false);
            $this->commands[$name] = $command;
        }
    }
}
