<?php

namespace Orchestra\View;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\View\Elements\Image\ImageElement;
use Twig\Environment;
use Orchestra\View\Twig\ConfigExtension;
use Orchestra\View\Twig\ElementsExtension;
use Orchestra\View\Twig\HighlighterExtension;
use Orchestra\View\Twig\MediaExtension;
use Orchestra\View\Twig\ThemeExtension;
use Orchestra\View\Twig\UrlExtension;
use Twig\Extra\String\StringExtension;
use Twig\Extension\StringLoaderExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\ChainLoader;

final class ViewComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('twig.loader', function ($c, $a): object {
            return new ChainLoader($a ?? []);
        });

        $container->add('twig', function ($c, $a): object {
            return new Environment(
                $c->get('twig.loader')
            );
        });

        $container->add('view.elements', function ($c, $a): object {
            return new ElementCollection();
        });
    }

    public function boot(Container $container): void
    {
        /** @var Environment */
        $twig = $container->get('twig');

        $twig->addExtension(new StringExtension());
        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new StringLoaderExtension());
        $twig->addExtension(new ConfigExtension(
            $container->get('compiler.context.provider')
        ));
        $twig->addExtension(new UrlExtension(
            $container->get('compiler.url')
        ));
        $twig->addExtension(new HighlighterExtension(
            $container->get('tempest.highlight')
        ));
        $twig->addExtension(new MediaExtension(
            $container->get('media.resolver'),
            $container->get('view.elements'),
            $container->get('compiler.url')
        ));
        $twig->addExtension(new ElementsExtension(
            $container->get('view.elements')
        ));
        $twig->addExtension(new ThemeExtension(
            $container->get('theme.assets'),
            $container->get('compiler.url')
        ));

        /** @var ElementCollection */
        $elements = $container->get('view.elements');

        $elements->add(new ImageElement(
            $twig,
            $container->get('content.repository'),
            $container->get('media.resolver'),
            $container->get('compiler.url')
        ));
    }
}
