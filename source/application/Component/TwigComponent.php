<?php

namespace GSpataro\Application\Component;

use GSpataro\DependencyInjection\Container;
use GSpataro\Solista\Component;
use Twig\Environment;
use GSpataro\View\TwigSitemap;
use GSpataro\View\TwigBlueprint;
use GSpataro\View\TwigHighlighter;
use Twig\Loader\FilesystemLoader;
use Twig\Extra\String\StringExtension;
use GSpataro\View\TwigGenerics;
use Twig\Extension\StringLoaderExtension;
use Twig\Extra\Intl\IntlExtension;

final class TwigComponent extends Component
{
    public function register(Container $container): void
    {
        $container->variable('twig.viewsPath', DIR_VIEW);

        $container->add('twig.loader', function ($container, $args): object {
            return new FilesystemLoader($container->variable('twig.viewsPath'));
        });

        $container->add('twig', function ($container, $args): object {
            return new Environment(
                $container->get('twig.loader')
            );
        });
    }

    public function boot(Container $container): void
    {
        $container->get('twig.loader')->addPath(DIR_ASSETS, 'assets');
        $twig = $container->get('twig');

        $twig->addExtension(new StringExtension());
        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new StringLoaderExtension());
        $twig->addExtension(new TwigGenerics(
            $container->get('assets.vite')
        ));
        $twig->addExtension(new TwigHighlighter(
            $container->get('tempest.highlight')
        ));
        $twig->addExtension(new TwigBlueprint(
            $container->get('project.blueprint')
        ));
        $twig->addExtension(new TwigSitemap(
            $container->get('project.blueprint'),
            $container->get('project.sitemap')
        ));
    }
}
