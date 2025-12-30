<?php

namespace GSpataro\Application\Component;

use Twig\Environment;
use GSpataro\View\TwigSitemap;
use GSpataro\View\TwigBlueprint;
use GSpataro\View\TwigHighlighter;
use Twig\Loader\FilesystemLoader;
use Twig\Extra\String\StringExtension;
use GSpataro\DependencyInjection\Component;
use GSpataro\View\TwigGenerics;
use Twig\Extension\StringLoaderExtension;
use Twig\Extra\Intl\IntlExtension;

final class TwigComponent extends Component
{
    public function register(): void
    {
        $this->container->variable('twig.viewsPath', DIR_VIEW);

        $this->container->add('twig.loader', function ($container, $args): object {
            return new FilesystemLoader($container->variable('twig.viewsPath'));
        });

        $this->container->add('twig', function ($container, $args): object {
            return new Environment(
                $container->get('twig.loader')
            );
        });
    }

    public function boot(): void
    {
        $this->container->get('twig.loader')->addPath(DIR_ASSETS, 'assets');
        $twig = $this->container->get('twig');

        $twig->addExtension(new StringExtension());
        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new StringLoaderExtension());
        $twig->addExtension(new TwigGenerics(
            $this->container->get('assets.vite')
        ));
        $twig->addExtension(new TwigHighlighter(
            $this->container->get('tempest.highlight')
        ));
        $twig->addExtension(new TwigBlueprint(
            $this->container->get('project.blueprint')
        ));
        $twig->addExtension(new TwigSitemap(
            $this->container->get('project.blueprint'),
            $this->container->get('project.sitemap')
        ));
    }
}
