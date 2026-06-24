<?php

namespace Orchestra\Compiler\Runtime;

use Exception;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Theme\ThemeLoader;
use Orchestra\Theme\ThemeProvider;
use Twig\Environment;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;

final class ThemeRuntime extends Runtime
{
    private Environment $twig;
    private ThemeLoader $themeLoader;
    private ChainLoader $twigLoader;
    private ThemeProvider $themeProvider;

    public function run(BuildOptions $options): bool
    {
        $this->twig = $this->container->get('twig');
        $this->themeLoader = $this->container->get('theme.loader');
        $this->twigLoader = $this->container->get('twig.loader');
        $this->themeProvider = $this->container->get('theme.provider');

        if (!$options->themeDebug) {
            $this->twig->setCache(
                $this->context->paths()->cache('twig')
            );
        }

        try {
            $theme = $this->themeLoader->load();
        } catch (Exception $e) {
            $this->output->error($e->getMessage());
            return false;
        }

        $this->themeProvider->set($theme);

        $themeLoader = new FilesystemLoader();
        $themeLoader->addPath($theme->path);

        if (is_dir($theme->elements())) {
            $themeLoader->addPath($theme->elements(), 'orchestra-elements');
        }

        $coreLoader = new FilesystemLoader();
        $coreLoader->addPath(dirname(__DIR__, 2) . '/View/Elements', 'orchestra-elements');
        $coreLoader->addPath(dirname(__DIR__, 2) . '/View/Layouts');

        $this->twigLoader->addLoader($coreLoader);
        $this->twigLoader->addLoader($themeLoader);

        return true;
    }
}
