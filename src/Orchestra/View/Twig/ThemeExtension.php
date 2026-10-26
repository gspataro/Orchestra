<?php

namespace Orchestra\View\Twig;

use Orchestra\Compiler\UrlGenerator;
use Orchestra\Theme\Assets\AssetRepository;
use Orchestra\Theme\ThemeLoader;
use Orchestra\Theme\ThemeProvider;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class ThemeExtension extends AbstractExtension
{
    public function __construct(
        private readonly ThemeProvider $themeProvider,
        private readonly AssetRepository $assets,
        private readonly UrlGenerator $url
    ) {
    }

    public function path(string $path): string
    {
        $theme = $this->themeProvider->get();

        return pathJoin($theme->path, $path);
    }

    /**
     * @param array<string,string> $default
     * @param array<string,string> $custom
     * @return string
     */
    public function attributes(array $default = [], array $custom = []): string
    {
        $attributes = array_merge($default, $custom);

        if (isset($default['class']) || isset($custom['class'])) {
            $attributes['class'] = trim($default['class'] ?? '') . ' ' . trim($custom['class'] ?? '');
        }

        $html = [];

        foreach ($attributes as $attribute => $value) {
            if (is_null($value)) {
                continue;
            }

            $html[] = sprintf('%s="%s"', $attribute, $value);
        }

        return implode(' ', $html);
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
        $functions[] = new TwigFunction('theme_path', [$this, 'path']);
        $functions[] = new TwigFunction('html_attributes', [$this, 'attributes'], [
            'is_safe' => ['html']
        ]);

        return $functions;
    }
}
