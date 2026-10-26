<?php

namespace Orchestra\Publisher\Adapter;

use Orchestra\Publisher\AdapterInterface;

final class HttpAdapter implements AdapterInterface
{
    public function handle(string $path, mixed $content): void
    {
        echo $content;
    }
}
