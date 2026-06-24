<?php

namespace Orchestra\Publisher;

interface AdapterInterface
{
    public function handle(string $path, mixed $content): void;
}
