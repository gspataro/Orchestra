<?php

namespace Orchestra\Publisher;

use Orchestra\Compiler\BuildContextProvider;

final class Publisher
{
    public function __construct(
        private readonly AdapterInterface $adapter
    ) {
    }

    public function publish(string $path, mixed $content): void
    {
        $this->adapter->handle($path, $content);
    }
}
