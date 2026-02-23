<?php

namespace Orchestra\View;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\View\Elements\Image\ImageElement;
use Twig\Environment;
use Orchestra\View\Twig\SitemapExtension;
use Orchestra\View\Twig\BlueprintExtension;
use Orchestra\View\Twig\ElementsExtension;
use Orchestra\View\Twig\HighlighterExtension;
use Orchestra\View\Twig\GenericsExtension;
use Orchestra\View\Twig\MediaExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Extra\String\StringExtension;
use Twig\Extension\StringLoaderExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\ChainLoader;

final class ViewComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('twig.loader', function ($container, $args): object {
            return new ChainLoader($args);
        });

        $container->add('twig', function ($container, $args): object {
            return new Environment(
                $container->get('twig.loader')
            );
        });

        $container->add('view.elements', function ($c, $a): object {
            return new ElementCollection();
        });
    }

    public function boot(Container $container): void
    {
        /** @var \Orchestra\Pipeline\BuildContext */
        $context = $container->get('pipeline.context');

        $themeLoader = new FilesystemLoader();

        if (is_dir($context->paths->views())) {
            $themeLoader->addPath($context->paths->views());
        }

        if (is_dir($context->paths->assets())) {
            $themeLoader->addPath($context->paths->assets(), 'assets');
        }

        if (is_dir($context->paths->views('elements'))) {
            $themeLoader->addPath($context->paths->views('elements'), 'orchestra-elements');
        }

        $coreLoader = new FilesystemLoader();
        $coreLoader->addPath(__DIR__ . '/Elements', 'orchestra-elements');

        /** @var FilesystemLoader */
        $container->get('twig.loader', [
            $themeLoader,
            $coreLoader
        ]);

        /** @var Environment */
        $twig = $container->get('twig');

        $twig->addExtension(new StringExtension());
        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new StringLoaderExtension());
        $twig->addExtension(new GenericsExtension(
            $container->get('assets.vite')
        ));
        $twig->addExtension(new HighlighterExtension(
            $container->get('tempest.highlight')
        ));
        $twig->addExtension(new BlueprintExtension(
            $container->get('pipeline.context')
        ));
        $twig->addExtension(new SitemapExtension(
            $container->get('pipeline.context')
        ));
        $twig->addExtension(new MediaExtension(
            $container->get('media.resolver'),
            $container->get('view.elements')
        ));
        $twig->addExtension(new ElementsExtension(
            $container->get('view.elements')
        ));

        /** @var ElementCollection */
        $elements = $container->get('view.elements');

        $elements->add(new ImageElement(
            $twig,
            $container->get('content.repository'),
            $container->get('media.resolver')
        ));
    }
}
