<?php

namespace GSpataro\Application\Component;

use GSpataro\Assets\Media;
use GSpataro\Assets\Vite;
use GSpataro\DependencyInjection\Component;

final class AssetsComponent extends Component
{
    public function register(): void
    {
        $this->container->add('assets.vite', function ($container, $args): object {
            return new Vite(
                $args['manifestPath'],
                $args['outputPath']
            );
        });

        $this->container->add('assets.media', function ($container, $args): object {
            return new Media();
        });
    }

    public function boot(): void
    {
        $vite = $this->container->get('assets.vite', [
            'manifestPath' => DIR_OUTPUT . '/assets/.vite/manifest.json',
            'outputPath' => '/assets/'
        ]);
        $vite->loadManifest();

        $media = $this->container->get('assets.media');

        $media->addSize('thumbnail', 400, 0, 90);
        $media->addSize('medium', 600, 0, 90);
        $media->addSize('large', 900, 0, 90);
        $media->addSize('full', 1920, 0, 90);
        $media->addSize('original', 0, 0, 90);
    }
}
