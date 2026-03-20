<?php

namespace Orchestra\Publisher;

use Orchestra\Publisher\BuilderInterface;

final class BuilderCollection
{
    /** @var array<string,BuilderInterface> */
    private array $builders = [];

    public function has(string $tag): bool
    {
        return isset($this->builders[$tag]);
    }

    public function add(string $tag, BuilderInterface $builder): void
    {
        if ($this->has($tag)) {
            throw new Exception\BuilderFoundException(
                "A builder with tag '{$tag}' already exists in the collection."
            );
        }

        $this->builders[$tag] = $builder;
    }

    public function get(string $tag): BuilderInterface
    {
        if (!$this->has($tag)) {
            throw new Exception\BuilderNotFoundException(
                "Builder with tag '{$tag}' not found in the collection."
            );
        }

        return $this->builders[$tag];
    }
}
