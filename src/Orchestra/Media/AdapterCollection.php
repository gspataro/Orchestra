<?php

namespace Orchestra\Media;

final class AdapterCollection
{
    /** @var string[] */
    private array $map = [];

    /** @var AdapterInterface[] */
    private array $adapters = [];

    public function has(string $tag): bool
    {
        return isset($this->adapters[$tag]);
    }

    public function add(AdapterInterface $adapter): void
    {
        if (!isset($this->adapters[$adapter::class])) {
            $this->adapters[$adapter::class] = $adapter;
        }

        foreach ($adapter->supports() as $mimeType) {
            $this->map[$mimeType] = $adapter::class;
        }
    }

    public function getFor(string $mimeType): ?AdapterInterface
    {
        if (isset($this->map[$mimeType])) {
            return $this->adapters[$this->map[$mimeType]];
        }

        if (isset($this->map['fallback'])) {
            return $this->adapters[$this->map['fallback']];
        }

        return null;
    }
}
