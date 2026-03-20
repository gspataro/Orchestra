<?php

namespace Orchestra\Test\Fixtures;

use Orchestra\Compiler\BuildOutputInterface;

final class TestOutputAdapter implements BuildOutputInterface
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
    }

    public function success(string $message): void
    {
    }
}
