<?php

namespace Orchestra\Rehearsal;

use Orchestra\Compiler\BuildOutputInterface;

final class RehearsalOutputAdapter implements BuildOutputInterface
{
    public function print(string $message): void
    {
    }

    public function info(string $message): void
    {
        error_log("{$message}\n");
    }

    public function warning(string $message): void
    {
    }

    public function error(string $message): void
    {
    }

    public function success(string $message): void
    {
    }
}
