<?php

namespace Orchestra\Compiler\Factory;

use GSpataro\DependencyInjection\Container;
use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\BuildOutputInterface;
use Orchestra\Compiler\Pipeline\BasePipeline;
use Orchestra\Compiler\PipelineInterface;

final class PipelineFactory
{
    public function __construct(
        private readonly Container $container,
        private readonly BuildContext $context
    ) {
    }

    /**
     * @param class-string<BasePipeline> $pipeline
     * @param BuildOutputInterface $output
     * @return BasePipeline
     */
    public function make(string $pipeline, BuildOutputInterface $output): PipelineInterface
    {
        return new $pipeline(
            $this->container,
            $this->context,
            $output
        );
    }
}
