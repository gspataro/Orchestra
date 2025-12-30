<?php

namespace GSpataro\Library;

use GSpataro\Library\Interface\ReaderInterface;

final class ReadersCollection
{
    /**
     * Store readers
     *
     * @var array
     */

    private array $readers = [];

    /**
     * Verify if a reader exists in the collection
     *
     * @param string $tag
     * @return bool
     */

    public function has(string $tag): bool
    {
        return isset($this->readers[$tag]);
    }

    /**
     * Add a reader to the collection
     *
     * @param string $tag
     * @param ReaderInterface $reader
     * @return void
     */

    public function add(string $tag, ReaderInterface $reader): void
    {
        if ($this->has($tag)) {
            throw new Exception\ReaderFoundException(
                "A reader with tag '{$tag}' already exists in the collection."
            );
        }

        $this->readers[$tag] = $reader;
    }

    /**
     * Get a reader
     *
     * @param string $tag
     * @return ReaderInterface
     */

    public function get(string $tag): ReaderInterface
    {
        if (!$this->has($tag)) {
            throw new Exception\ReaderNotFoundException(
                "Reader with tag '{$tag}' not found in the collection."
            );
        }

        return $this->readers[$tag];
    }
}
