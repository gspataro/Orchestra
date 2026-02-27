<?php

namespace Orchestra\View\Twig;

use Orchestra\Compiler\UrlGenerator;
use Orchestra\Theme\Assets\AssetRepository;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class ThemeExtension extends AbstractExtension
{
    public function __construct(
        private readonly AssetRepository $assets,
        private readonly UrlGenerator $url
    ) {
    }

    public function css(): string
    {
        $css = $this->assets->css();
        $links = '';

        foreach ($css as $entry) {
            $links .= "<link rel=\"stylesheet\" href=\"{$this->url->to($entry)}\">\n";
        }

        return $links;
    }

    public function js(): string
    {
        $js = $this->assets->js();
        $scripts = '';

        foreach ($js as $entry) {
            $scripts .= "<script type=\"text/javascript\" src=\"{$this->url->to($entry)}\"></script>\n";
        }

        return $scripts;
    }

    public function getFunctions()
    {
        $functions = [];

        $functions[] = new TwigFunction('theme_css', [$this, 'css'], [
            'is_safe' => ['html']
        ]);
        $functions[] = new TwigFunction('theme_js', [$this, 'js'], [
            'is_safe' => ['html']
        ]);

        return $functions;
    }
}
