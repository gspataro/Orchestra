<?php

namespace Orchestra\View\Twig;

use Orchestra\Theme\Assets\AssetRepository;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class ThemeExtension extends AbstractExtension
{
    public function __construct(
        private readonly AssetRepository $assets
    ) {
    }

    public function css(): string
    {
        $css = $this->assets->css();
        $links = '';

        foreach ($css as $entry) {
            $entry = pathJoin(getenv('WEBSITE_URL'), $entry);
            $links .= "<link rel=\"stylesheet\" href=\"{$entry}\">\n";
        }

        return $links;
    }

    public function js(): string
    {
        $js = $this->assets->js();
        $scripts = '';

        foreach ($js as $entry) {
            $entry = pathJoin(getenv('WEBSITE_URL'), $entry);
            $scripts .= "<script type=\"text/javascript\" src=\"{$entry}\"></script>\n";
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
