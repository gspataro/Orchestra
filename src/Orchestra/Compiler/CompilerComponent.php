<?php

namespace Orchestra\Compiler;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;

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
            return new Pipeline(
                $c,
                $c->get('compiler.context'),
                $a['output.adapter']
            );
        });

        $container->add('compiler.options', function ($c, $a): object {
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
