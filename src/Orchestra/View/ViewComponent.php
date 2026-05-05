<?php

namespace Orchestra\View;

use GSpataro\DependencyInjection\Container;
use Orchestra\Application\Component;
use Orchestra\View\Elements\Audio\AudioElement;
use Orchestra\View\Elements\Image\ImageElement;
use Orchestra\View\Elements\Svg\SvgElement;
use Orchestra\View\Elements\Link\LinkElement;
use Twig\Environment;
use Orchestra\View\Twig\ConfigExtension;
use Orchestra\View\Twig\ContentExtension;
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

        $container->add('view.elements.renderer', function ($c, $a): object {
            return new ElementsRenderer(
                $c->get('view.elements')
            );
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
            $container->get('view.elements'),
            $container->get('view.elements.renderer')
        ));
        $twig->addExtension(new ThemeExtension(
            $container->get('theme.provider'),
            $container->get('theme.assets'),
            $container->get('compiler.url')
        ));
        $twig->addExtension(new ContentExtension());

        /** @var ElementCollection */
        $elements = $container->get('view.elements');

        $elements->add(new ImageElement(
            $twig,
            $container->get('content.repository'),
            $container->get('media.resolver'),
            $container->get('compiler.url')
        ));

        $elements->add(new LinkElement(
            $twig,
            $container->get('content.repository'),
            $container->get('media.resolver'),
            $container->get('compiler.url')
        ));

        $elements->add(new SvgElement(
            $twig,
            $container->get('content.repository'),
            $container->get('media.resolver'),
            $container->get('compiler.url')
        ));

        $elements->add(new AudioElement(
            $twig,
            $container->get('content.repository'),
            $container->get('media.resolver'),
            $container->get('compiler.url')
        ));
    }
}
