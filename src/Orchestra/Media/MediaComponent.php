<?php

namespace Orchestra\Media;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Media\Adapter\CopyAdapter;
use Orchestra\Media\Adapter\ImageAdapter;
use Orchestra\Media\AdapterCollection;
use Orchestra\Media\MediaRepository;
use Orchestra\Media\MediaResolver;

final class MediaComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('media.repository', function ($c, $a): object {
            return new MediaRepository();
        });

        $container->add('media.adapters', function ($c, $a): object {
            return new AdapterCollection();
        });

        $container->add('media.resolver', function ($c, $a): object {
            return new MediaResolver(
                $c->get('pipeline.context'),
                $c->get('media.repository')
            );
        });
    }

    public function boot(Container $container): void
    {
        /** @var AdapterCollection */
        $adapters = $container->get('media.adapters');

        $adapters->add([
            'image/jpeg',
            'image/png'
        ], new ImageAdapter());

        $adapters->add('default', new CopyAdapter());
    }
}
