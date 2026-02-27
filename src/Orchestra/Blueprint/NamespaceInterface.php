<?php

namespace Orchestra\Blueprint;

interface NamespaceInterface
{
    public function get(string $id): mixed;
    public function all(): array;
}
