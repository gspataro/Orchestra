<?php

namespace Orchestra\Assets;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;

final class AssetsComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('assets.vite', function ($container, $args): object {
            return new Vite(
                $args['manifestPath'],
                $args['outputPath']
            );
        });
    }

    public function boot(Container $container): void
    {
        /** @var \Orchestra\Pipeline\BuildContext */
        $context = $container->get('pipeline.context');

        /** @var Vite */
        $vite = $container->get('assets.vite', [
            'manifestPath' => $context->paths->output('/assets/.vite/manifest.json'),
            'outputPath' => '/assets/'
        ]);
        $vite->loadManifest();
    }
}
