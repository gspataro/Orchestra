<?php

namespace Orchestra\Content;

use Orchestra\Utilities\DotNavigator;

final class Content extends DotNavigator
{
    protected bool $readOnly = true;

    /**
     * @param string $id
     * @param string $tag
     * @param string $group
     * @param string $path
     * @param mixed $body
     * @param array<string|int,mixed> $metadata
     */
    public function __construct(
        public readonly string $id,
        public readonly string $tag,
        public readonly string $group,
        public readonly string $path,
        public readonly mixed $body,
        public readonly array $metadata = [],
    ) {
        $this->fill([
            'id' => $this->id,
            'tag' => $this->tag,
            'group' => $this->group,
            'path' => $this->path,
            'body' => $this->body,
            'metadata' => $this->metadata
        ]);
    }

    /**
     * @param ContentCollection[]
     * @return self
     */
    public function withRelationships(array $relationships): self
    {
        return new self(
            $this->id,
            $this->tag,
            $this->group,
            $this->path,
            $this->body,
            array_merge($this->metadata, $relationships)
        );
    }
}
