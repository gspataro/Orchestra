<?php

namespace Orchestra\Pipeline;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;

final class PipelineComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('pipeline.context', function ($c, $a): object {
            return new BuildContext();
        });
    }

    public function boot(Container $container): void
    {
        /** @var BuildContext */
        $context = $container->get('pipeline.context');

        $context->setContext(
            $container->get('project.blueprint'),
            $container->get('project.prototype'),
            $container->get('project.sitemap')
        );
    }
}
