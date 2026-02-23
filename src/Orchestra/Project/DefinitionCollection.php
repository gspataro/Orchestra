<?php

namespace Orchestra\Project;

final class DefinitionCollection
{
    /** @var DefinitionInterface[] */
    private array $definitions = [];

    public function add(DefinitionInterface $configDefinition): void
    {
        $this->definitions[$configDefinition->namespace()] = $configDefinition;
    }

    public function get(string $namespace): ?DefinitionInterface
    {
        return $this->definitions[$namespace] ?? null;
    }

    /**
     * @return DefinitionInterface[]
     */
    public function all(): array
    {
        return $this->definitions;
    }
}
