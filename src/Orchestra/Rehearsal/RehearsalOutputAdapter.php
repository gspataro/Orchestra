<?php

namespace Orchestra\Rehearsal;

use Orchestra\Compiler\BuildOutputInterface;
use Orchestra\Rehearsal\Exception\RehearsalException;

final class RehearsalOutputAdapter implements BuildOutputInterface
{
    public function print(string $message): void
    {
    }

    public function info(string $message): void
    {
    }

    public function warning(string $message): void
    {
    }

    public function error(string $message): void
    {
        throw new RehearsalException($message);
    }

    public function success(string $message): void
    {
    }
}
