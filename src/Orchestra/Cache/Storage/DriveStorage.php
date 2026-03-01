<?php

namespace Orchestra\Cache\Storage;

use Orchestra\Cache\CacheSession;
use Orchestra\Cache\CacheStorageInterface;
use Orchestra\Compiler\BuildContext;

final class DriveStorage implements CacheStorageInterface
{
    public function __construct(
        private readonly BuildContext $context,
        private readonly CacheSession $session
    ) {
    }

    private function getPath(string $namespace, string $key): string
    {
        return $this->context->paths()->cache(
            'orchestra',
            $namespace,
            substr($key, 0, 2),
            $key . '.json'
        );
    }

    public function has(string $namespace, string $key): bool
    {
        return is_file($this->getPath($namespace, $key));
    }

    public function get(string $namespace, string $key): ?string
    {
        if (!$this->has($namespace, $key)) {
            return null;
        }

        $path = $this->getPath($namespace, $key);
        $this->session->add($path);

        return file_get_contents($path);
    }

    public function save(string $namespace, string $key, string $data): void
    {
        $path = $this->getPath($namespace, $key);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        file_put_contents($path, $data);

        $this->session->add($path);
    }

    public function delete(string $namespace, string $key): void
    {
        unlink($this->getPath($namespace, $key));
    }
}
