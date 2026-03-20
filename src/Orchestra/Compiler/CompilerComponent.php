<?php

namespace Orchestra\Compiler;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Compiler\Factory\BuildContextFactory;
use Orchestra\Compiler\Factory\PipelineFactory;
use Orchestra\Compiler\Pipeline\BuildPipeline;
use Orchestra\Compiler\Pipeline\RehearsalPipeline;

final class CompilerComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('compiler.context.provider', function ($c, $a): object {
            return new BuildContextProvider();
        });

        $container->add('compiler.context.factory', function ($c, $a): object {
            return new BuildContextFactory();
        });

        $container->add('compiler.pipeline.factory', function ($c, $a): object {
            return new PipelineFactory($c);
        });

        $container->add('compiler.options', function ($c, $a): object {
            return new BuildOptions(...$a);
        });

        $container->add('compiler.url', function ($c, $a): object {
            return new UrlGenerator(
                $c->get('compiler.context.provider')
            );
        });
    }

    public function boot(Container $container): void
    {
    }
}
