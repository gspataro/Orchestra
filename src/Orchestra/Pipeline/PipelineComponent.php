<?php

namespace Orchestra\Pipeline;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;

final class PipelineComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('pipeline.context', function ($c, $a): object {
            return new BuildContext($a['paths'] ?? null);
        });

        $container->add('pipeline.paths', function ($c, $a): object {
            return new Paths($a['root'] ?? getcwd());
        });

        $container->add('pipeline', function ($c, $a): object {
            return new Pipeline(
                $c,
                $c->get('pipeline.context'),
                $a['output.adapter']
            );
        });

        $container->add('pipeline.options', function ($c, $a): object {
            return new BuildOptions(
                $a['skipMedia'] ?? false,
                $a['cleanupOnly'] ?? false
            );
        });
    }

    public function boot(Container $container): void
    {
    }
}
