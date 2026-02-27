<?php

namespace Orchestra\Blueprint;

interface SpecificationInterface
{
    public function namespace(): string;
    public function definition(): array;
    public function createNamespace(array $data): NamespaceInterface;
}
