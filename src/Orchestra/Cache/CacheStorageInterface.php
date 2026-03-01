<?php

namespace Orchestra\Cache;

interface CacheStorageInterface
{
    public function has(string $namespace, string $key): bool;
    public function get(string $namespace, string $key): ?string;
    public function save(string $namespace, string $key, string $data): void;
    public function delete(string $namespace, string $key): void;
}
