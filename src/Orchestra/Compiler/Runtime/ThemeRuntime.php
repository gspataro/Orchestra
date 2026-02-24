<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Compiler\BuildOptions;
use Orchestra\Theme\ThemeLoader;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;

final class ThemeRuntime extends Runtime
{
    private readonly ThemeLoader $themeLoader;
    private readonly ChainLoader $twigLoader;

    public function run(BuildOptions $options): bool
    {
        $this->themeLoader = $this->container->get('theme.loader');
        $this->twigLoader = $this->container->get('twig.loader');

        $theme = $this->themeLoader->load();

        $themeLoader = new FilesystemLoader();
        $themeLoader->addPath($theme->path);

        if (is_dir($theme->assets())) {
            $themeLoader->addPath($theme->assets(), 'assets');
        }

        if (is_dir($theme->elements())) {
            $themeLoader->addPath($theme->elements(), 'orchestra-elements');
        }

        $coreLoader = new FilesystemLoader();
        $coreLoader->addPath(dirname(__DIR__, 2) . '/View/Elements', 'orchestra-elements');

        $this->twigLoader->addLoader($coreLoader);
        $this->twigLoader->addLoader($themeLoader);

        return true;
    }
}
