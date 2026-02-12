<?php

namespace Orchestra\Content;

use Orchestra\Utilities\DotNavigator;

final class Content extends DotNavigator
{
    protected bool $readOnly = true;

    public function __construct(
        public readonly string $id,
        public readonly string $tag,
        public readonly string $group,
        public readonly string $path,
        public readonly mixed $body,
        public readonly array $metadata = []
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
}
