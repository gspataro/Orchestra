<?php

namespace Orchestra\Console;

use GSpataro\CLI\Interface\OutputInterface;
use Orchestra\Pipeline\BuildOutputInterface;

final class ConsoleOutputAdapter implements BuildOutputInterface
{
    public function __construct(
        private readonly OutputInterface $output
    ) {
    }

    public function print(string $message): void
    {
        $this->output->print($message);
    }

    public function info(string $message): void
    {
        $this->output->print("{bold}{$message}");
    }

    public function warning(string $message): void
    {
        $this->output->print("{bold}{fg_yellow}{$message}");
    }

    public function error(string $message): void
    {
        $this->output->print("{bold}{fg_red}{$message}");
    }

    public function success(string $message): void
    {
        $this->output->print("{bold}{fg_green}{$message}");
    }
}
