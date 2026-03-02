<?php

namespace Orchestra\Compiler;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Compiler\Factory\PipelineFactory;
use Orchestra\Compiler\Pipeline\BuildPipeline;
use Orchestra\Compiler\Pipeline\RehearsalPipeline;

final class CompilerComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('compiler.context', function ($c, $a): object {
            return new BuildContext($a['paths'] ?? null);
        });

        $container->add('compiler.paths', function ($c, $a): object {
            return new Paths($a['root'] ?? getcwd());
        });

        $container->add('compiler.pipeline.factory', function ($c, $a): object {
            return new PipelineFactory(
                $c,
                $c->get('compiler.context')
            );
        });

        $container->add('compiler.pipeline', function ($c, $a): object {
            return new PipelineCollection(
                $c->get('compiler.pipeline.factory')
            );
        });

        $container->add('compiler.options', function ($c, $a): object {
            return new BuildOptions(...$a);
        });

        $container->add('compiler.url', function ($c, $a): object {
            return new UrlGenerator(
                $c->get('compiler.context')
            );
        });
    }

    public function boot(Container $container): void
    {
        /** @var PipelineCollection */
        $pipeline = $container->get('compiler.pipeline');

        $pipeline->add('build', BuildPipeline::class);
        $pipeline->add('preview', RehearsalPipeline::class);
    }
}
