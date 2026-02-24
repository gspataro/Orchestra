<?php

namespace Orchestra\Media;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Media\Adapter\CopyAdapter;
use Orchestra\Media\Adapter\ImageAdapter;
use Orchestra\Media\AdapterCollection;
use Orchestra\Media\MediaRepository;
use Orchestra\Media\MediaResolver;
use Orchestra\Media\Policy\ImagePolicy;

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

        $container->add('media.policies', function ($c, $a): object {
            return new PolicyCollection();
        });

        $container->add('media.transformer', function ($c, $a): object {
            return new MediaTransformer(
                $c->get('compiler.context')
            );
        });

        $container->add('media.resolver', function ($c, $a): object {
            return new MediaResolver(
                $c->get('compiler.context'),
                $c->get('media.repository'),
                $c->get('media.transformer'),
                $c->get('media.policies')
            );
        });
    }

    public function boot(Container $container): void
    {
        /** @var AdapterCollection */
        $adapters = $container->get('media.adapters');

        $adapters->add(new ImageAdapter());
        $adapters->add(new CopyAdapter());

        /** @var PolicyCollection */
        $policies = $container->get('media.policies');

        $policies->add(new ImagePolicy());
    }
}
