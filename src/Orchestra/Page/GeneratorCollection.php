<?php

namespace Orchestra\Page;

use Orchestra\Page\GeneratorInterface;

final class GeneratorCollection
{
    /** @var GeneratorInterface[] */
    private array $generators = [];

    public function has(string $tag): bool
    {
        return isset($this->generators[$tag]);
    }

    public function add(string $tag, GeneratorInterface $generator): void
    {
        if ($this->has($tag)) {
            throw new Exception\GeneratorFoundException(
                "A generator with tag '{$tag}' already exists in the collection."
            );
        }

        $this->generators[$tag] = $generator;
    }

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
