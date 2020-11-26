<?php

declare(strict_types = 1);

namespace App\Console;

use App\IO\TerminalInput;
use App\IO\TerminalOutput;

class CommandRunner
{
    private array $commands;

    static array $refObjs;

    public function __construct(array $commands)
    {
        $this->registerCommands($commands);
    }

    public function run(array $args)
    {
        $name = $args[0];

        if (!isset($this->commands[$name])) {
            throw new CommandNotFoundException($name);
        }

        $command = $this->commands[$name];
        $arguments = self::getSetInaccessibleProp($command, 'arguments');
        $args = array_slice($args, 1);

        if (count($args) < count($arguments)) {
            throw new ConsoleException(
                'Too few arguments for the command. Expects ' . count($arguments)
            );
        }

        self::getSetInaccessibleProp(
            $command,
            'arguments',
            array_combine(
                array_keys($arguments),
                array_slice($args, 0, count($arguments))
            )
        );

        $command->execute(
            new TerminalInput(),
            new TerminalOutput()
        );
    }

    /**
     * @param Command[] $commands
     */
    private function registerCommands(array $commands)
    {
        foreach ($commands as $command) {
            $name = self::getSetInaccessibleProp($command, 'name');
            $this->commands[$name] = $command;

            $refObj = self::getRefObj($command);

            if ($refObj->hasMethod('setRunner')) {
                $command->setRunner($this);
            }
        }
    }

    /**
     * @param Command $command
     * @param string $name
     * @param null $value
     * @return mixed|null
     * @throws \ReflectionException
     */
    public static function getSetInaccessibleProp(
        Command $command,
        string $name,
        $value = null
    ) {
        $refObj = self::getRefObj($command);
        $prop = $refObj->getProperty($name);
        $prop->setAccessible(true);

        if (!$value) {
            $value = $prop->getValue($command);
        } else {
            $prop->setValue($command, $value);
        }

        $prop->setAccessible(false);

        return $value;
    }

    private static function getRefObj(Command $command): \ReflectionObject
    {
        $id = spl_object_id($command);

        if (!isset(self::$refObjs[$id])) {
            self::$refObjs[$id] = new \ReflectionObject($command);
        }

        return self::$refObjs[$id];
    }
}
