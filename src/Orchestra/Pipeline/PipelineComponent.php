<?php

namespace Orchestra\Pipeline;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;

final class PipelineComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('pipeline.context', function ($c, $a): object {
            return new BuildContext($a['root'] ?? getcwd());
        });
    }

    public function boot(Container $container): void
    {
    }
}
