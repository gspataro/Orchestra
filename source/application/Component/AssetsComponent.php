<?php

namespace GSpataro\Application\Component;

use GSpataro\Assets\Media;
use GSpataro\Assets\Vite;
use GSpataro\DependencyInjection\Container;
use GSpataro\Solista\Component;

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

        $container->add('assets.media', function ($container, $args): object {
            return new Media();
        });
    }

    public function boot(Container $container): void
    {
        $vite = $container->get('assets.vite', [
            'manifestPath' => DIR_OUTPUT . '/assets/.vite/manifest.json',
            'outputPath' => '/assets/'
        ]);
        $vite->loadManifest();

        $media = $container->get('assets.media');

        $media->addSize('thumbnail', 400, 0, 90);
        $media->addSize('medium', 600, 0, 90);
        $media->addSize('large', 900, 0, 90);
        $media->addSize('full', 1920, 0, 90);
        $media->addSize('original', 0, 0, 90);
    }
}
