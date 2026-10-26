<?php

namespace Orchestra\Rehearsal;

use Orchestra\Page\Page;

final readonly class Response
{
    /**
     * @param ResponseType $type
     * @param array<string, mixed> $headers
     * @param Page|string|null $payload
     */
    public function __construct(
        public ResponseType $type,
        public array $headers = [],
        public Page|string|null $payload = null
    ) {
    }
}
