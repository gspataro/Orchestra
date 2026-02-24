<?php

namespace Orchestra\Compiler;

final class PipelineCollection
{
    /** @var PipelineInterface */
    private array $pipelines = [];

    public function get(string $name): ?PipelineInterface
    {
        return $this->pipelines[$name] ?? null;
    }

    public function add(string $name, PipelineInterface $pipeline): void
    {
        if (isset($this->pipelines[$name])) {
            return;
        }

        $this->pipelines[$name] = $pipeline;
    }
}
