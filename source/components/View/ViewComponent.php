<?php

namespace GSpataro\View;

use GSpataro\DependencyInjection\Container;
use GSpataro\Solista\Component;
use Twig\Environment;
use GSpataro\View\Twig\TwigSitemap;
use GSpataro\View\Twig\TwigBlueprint;
use GSpataro\View\Twig\TwigHighlighter;
use GSpataro\View\Twig\TwigGenerics;
use Twig\Loader\FilesystemLoader;
use Twig\Extra\String\StringExtension;
use Twig\Extension\StringLoaderExtension;
use Twig\Extra\Intl\IntlExtension;

final class ViewComponent extends Component
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
