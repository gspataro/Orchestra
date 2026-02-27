<?php

namespace Orchestra\Compiler;

use Orchestra\Compiler\Factory\PipelineFactory;

final class PipelineCollection
{
    /** @var array<class-string<PipelineInterface>> */
    private array $pipelines = [];

    public function __construct(
        private readonly PipelineFactory $pipelineFactory
    ) {
    }

    public function get(string $name, BuildOutputInterface $output): ?PipelineInterface
    {
        if (!$this->pipelines[$name]) {
            return null;
        }

        return $this->pipelineFactory->make($this->pipelines[$name], $output);
    }

    /**
     * @param string $name
     * @param class-string<PipelineInterface> $pipeline
     * @return void
     */

    public function add(string $name, string $pipeline): void
    {
        if (isset($this->pipelines[$name])) {
            return;
        }

        $this->pipelines[$name] = $pipeline;
    }
}
