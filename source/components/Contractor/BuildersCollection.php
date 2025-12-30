<?php

namespace GSpataro\Contractor;

use GSpataro\Contractor\Interface\BuilderInterface;

final class BuildersCollection
{
    /**
     * Store builders
     *
     * @var array
     */

    private array $builders = [];

    /**
     * Verify if a builder exists in the collection
     *
     * @param string $tag
     * @return bool
     */

    public function has(string $tag): bool
    {
        return isset($this->builders[$tag]);
    }

    /**
     * Add a builder to the collection
     *
     * @param string $tag
     * @param BuilderInterface $builder
     * @return void
     */

    public function add(string $tag, BuilderInterface $builder): void
    {
        if ($this->has($tag)) {
            throw new Exception\BuilderFoundException(
                "A builder with tag '{$tag}' already exists in the collection."
            );
        }

        $this->builders[$tag] = $builder;
    }

    /**
     * Get a builder
     *
     * @param string $tag
     * @return BuilderInterface
     */

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
