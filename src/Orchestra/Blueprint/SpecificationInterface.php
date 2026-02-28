<?php

namespace Orchestra\Blueprint;

interface SpecificationInterface
{
    public function namespace(): string;

    /**
     * @return array<string,array<string,string|int|bool|array<string|int,mixed>>>
     */
    public function definition(): array;

    /**
     * @param array<string|int,mixed> $data
     * @return NamespaceInterface
     */
    public function createNamespace(array $data): NamespaceInterface;
}
