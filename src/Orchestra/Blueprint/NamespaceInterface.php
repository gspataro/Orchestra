<?php

namespace Orchestra\Blueprint;

interface NamespaceInterface
{
    public function get(string $id): mixed;

    /**
     * @return array<string|int,mixed>
     */
    public function all(): array;
}
