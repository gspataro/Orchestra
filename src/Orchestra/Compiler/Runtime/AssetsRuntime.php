<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Compiler\BuildOptions;
use Orchestra\Compiler\CompilerMode;
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
        $publish = $this->context->options()->mode !== CompilerMode::REHEARSAL;

        if (!$driver) {
            $this->output->warning("Asset driver '{$theme->assets->driver}' not found");
            return true;
        }

        $driver->discover($theme);

        $currentEntries = [];

        foreach ($driver->entries() as $entry) {
            if ($entry->autoload && in_array($entry->type, ['css', 'js'])) {
                $this->assets->add(
                    $entry->type,
                    pathJoin('assets', $entry->publicPath) . ($publish ? '' : '?v=' . time())
                );
            }

            $currentEntries[] = $this->context->paths()->output('assets', $entry->publicPath);

            if ($publish) {
                copy(
                    pathJoin($theme->path, $theme->assets->dir, $entry->publicPath),
                    $this->context->paths()->output('assets', $entry->publicPath)
                );
            }
        }

        if ($publish) {
            recursiveDelete($this->context->paths()->output('assets'), true, $currentEntries);
        }

        return true;
    }
}
