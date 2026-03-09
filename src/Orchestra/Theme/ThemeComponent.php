<?php

namespace Orchestra\Theme;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\Theme\Assets\AssetRepository;
use Orchestra\Theme\Assets\Driver\StaticDriver;
use Orchestra\Theme\Assets\Driver\ViteDriver;
use Orchestra\Theme\Assets\DriverCollection;
use Orchestra\Theme\ThemeLoader;

final class ThemeComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('theme.loader', function ($c, $a): object {
            return new ThemeLoader(
                $c->get('compiler.context.provider')->get()
            );
        });

        $container->add('theme.assets.drivers', function ($c, $a): object {
            return new DriverCollection();
        });

        $container->add('theme.assets', function ($c, $a): object {
            return new AssetRepository();
        });
    }

    public function boot(Container $container): void
    {
        /** @var DriverCollection */
        $drivers = $container->get('theme.assets.drivers');

        $drivers->add('static', new StaticDriver());
        $drivers->add('vite', new ViteDriver());
    }
}
