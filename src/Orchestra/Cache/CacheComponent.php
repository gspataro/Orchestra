<?php

namespace Orchestra\Cache;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Cache\SignatureGenerator\ShaSignatureGenerator;
use Orchestra\Cache\Storage\DriveStorage;

final class CacheComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('cache.session', function ($c, $a): object {
            return new CacheSession();
        });

        $container->add('cache.storage', function ($c, $a): object {
            return new DriveStorage(
                $c->get('compiler.context'),
                $c->get('cache.session')
            );
        });

        $container->add('cache.signature', function ($c, $a): object {
            return new ShaSignatureGenerator();
        });
    }

    public function boot(Container $container): void
    {
    }
}
