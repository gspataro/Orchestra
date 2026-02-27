<?php

namespace Orchestra\Compiler;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Compiler\Pipeline\BuildPipeline;

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

        $container->add('compiler.pipeline', function ($c, $a): object {
            return new PipelineCollection();
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

        $pipeline->add('build', new BuildPipeline(
            $container,
            $container->get('compiler.context')
        ));
    }
}
