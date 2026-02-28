<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Compiler\BuildOptions;
use Orchestra\Theme\Assets\AssetRepository;
use Orchestra\Theme\Assets\DriverCollection;
use Orchestra\Theme\ThemeLoader;

final class AssetsRuntime extends Runtime
{
    private ThemeLoader $themeLoader;
    private DriverCollection $drivers;
    private AssetRepository $assets;

    public function run(BuildOptions $options): bool
    {
        $this->output->info("Copying assets");

        $this->drivers = $this->container->get('theme.assets.drivers');
        $this->themeLoader = $this->container->get('theme.loader');
        $this->assets = $this->container->get('theme.assets');

        $theme = $this->themeLoader->load();
        $driver = $this->drivers->get($theme->assets->driver);

        if (!$driver) {
            $this->output->warning("Asset driver not found");
            return true;
        }

        $driver->build($theme, $this->context);

        foreach ($driver->css() as $css) {
            $this->assets->add('css', $css);
        }

        foreach ($driver->js() as $js) {
            $this->assets->add('js', $js);
        }

        return true;
    }
}
