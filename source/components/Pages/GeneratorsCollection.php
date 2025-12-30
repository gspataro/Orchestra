<?php

namespace GSpataro\Pages;

use GSpataro\Pages\Interface\GeneratorInterface;

final class GeneratorsCollection
{
    /**
     * Store generators
     *
     * @var array
     */

    private array $generators = [];

    /**
     * Verify if a generator exists in the collection
     *
     * @param string $tag
     * @return bool
     */

    public function has(string $tag): bool
    {
        return isset($this->generators[$tag]);
    }

    /**
     * Add a generator to the collection
     *
     * @param string $tag
     * @param GeneratorInterface $generator
     * @return void
     */

    public function add(string $tag, GeneratorInterface $generator): void
    {
        if ($this->has($tag)) {
            throw new Exception\GeneratorFoundException(
                "A generator with tag '{$tag}' already exists in the collection."
            );
        }

        $this->generators[$tag] = $generator;
    }

    /**
     * Get a generator
     *
     * @param string $tag
     * @return GeneratorInterface
     */

    public function get(string $tag): GeneratorInterface
    {
        if (!$this->has($tag)) {
            throw new Exception\GeneratorNotFoundException(
                "Generator with tag '{$tag}' not found in the collection."
            );
        }

        return $this->generators[$tag];
    }
}
