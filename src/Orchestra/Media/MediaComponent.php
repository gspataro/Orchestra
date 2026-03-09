<?php

namespace Orchestra\Media;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Media\Adapter\CopyAdapter;
use Orchestra\Media\Adapter\ImageAdapter;
use Orchestra\Media\AdapterCollection;
use Orchestra\Media\Cache\MediaCacheRepository;
use Orchestra\Media\Cache\MediaIndexRepository;
use Orchestra\Media\Cache\MediaRepositorySerializer;
use Orchestra\Media\Cache\MediaSerializer;
use Orchestra\Media\Cache\MediaSignatureGenerator;
use Orchestra\Media\Factory\MediaTransformationFactory;
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

        $container->add('media.transformation.factory', function ($c, $a): object {
            return new MediaTransformationFactory();
        });

        $container->add('media.transformer', function ($c, $a): object {
            return new MediaTransformer(
                $c->get('compiler.context.provider'),
                $c->get('media.transformation.factory')
            );
        });

        $container->add('media.resolver', function ($c, $a): object {
            return new MediaResolver(
                $c->get('compiler.context.provider'),
                $c->get('media.repository'),
                $c->get('media.transformer'),
                $c->get('media.policies')
            );
        });

        $container->add('media.repository.serializer', function ($c, $a): object {
            return new MediaRepositorySerializer();
        });

        $container->add('media.cache.index', function ($c, $a): object {
            return new MediaIndexRepository(
                $c->get('cache.storage'),
                $c->get('media.repository.serializer')
            );
        });

        $container->add('media.serializer', function ($c, $a): object {
            return new MediaSerializer();
        });

        $container->add('media.signature', function ($c, $a): object {
            return new MediaSignatureGenerator(
                $c->get('cache.signature')
            );
        });

        $container->add('media.cache', function ($c, $a): object {
            return new MediaCacheRepository(
                $c->get('cache.storage'),
                $c->get('media.serializer'),
                $c->get('media.signature')
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
