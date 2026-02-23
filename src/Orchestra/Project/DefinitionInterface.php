<?php

namespace Orchestra\Project;

interface DefinitionInterface
{
    public function namespace(): string;
    public function schema(): array;
    public function validate(array $configs): array;
}
