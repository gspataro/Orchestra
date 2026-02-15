<?php

namespace Orchestra\Media;

final class AdapterCollection
{
    /** @var string[] */
    private array $map = [];

    /** @var AdapterInterface */
    private array $adapters = [];

    public function has(string $tag): bool
    {
        return isset($this->adapters[$tag]);
    }

    public function add(array|string $mimeType, AdapterInterface $adapter): void
    {
        if (!isset($this->adapters[$adapter::class])) {
            $this->adapters[$adapter::class] = $adapter;
        }

        if (!is_array($mimeType)) {
            $this->map[$mimeType] = $adapter::class;
            return;
        }

        foreach ($mimeType as $mime) {
            $this->map[$mime] = $adapter::class;
        }
    }

    public function getFor(string $mimeType): ?AdapterInterface
    {
        if (isset($this->map[$mimeType])) {
            return $this->adapters[$this->map[$mimeType]];
        }

        if (isset($this->map['default'])) {
            return $this->adapters[$this->map['default']];
        }

        return null;
    }
}
