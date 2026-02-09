<?php

namespace Orchestra\View;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Twig\Environment;
use Orchestra\View\Twig\TwigSitemap;
use Orchestra\View\Twig\TwigBlueprint;
use Orchestra\View\Twig\TwigHighlighter;
use Orchestra\View\Twig\TwigGenerics;
use Twig\Loader\FilesystemLoader;
use Twig\Extra\String\StringExtension;
use Twig\Extension\StringLoaderExtension;
use Twig\Extra\Intl\IntlExtension;

final class ViewComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('twig.loader', function ($container, $args): object {
            return new FilesystemLoader();
        });

        $container->add('twig', function ($container, $args): object {
            return new Environment(
                $container->get('twig.loader')
            );
        });
    }

    public function boot(Container $container): void
    {
        /** @var \Orchestra\Pipeline\BuildContext */
        $context = $container->get('pipeline.context');

        /** @var FilesystemLoader */
        $twigLoader = $container->get('twig.loader');

        if (is_dir($context->paths->views())) {
            $twigLoader->addPath($context->paths->views());
        }

        if (is_dir($context->paths->assets())) {
            $twigLoader->addPath($context->paths->assets(), 'assets');
        }

        /** @var TwigEnvironment */
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
            $container->get('pipeline.context')
        ));
        $twig->addExtension(new TwigSitemap(
            $container->get('pipeline.context')
        ));
    }
}
