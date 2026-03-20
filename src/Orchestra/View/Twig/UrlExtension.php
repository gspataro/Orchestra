<?php

namespace Orchestra\View\Twig;

use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\UrlGenerator;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class UrlExtension extends AbstractExtension
{
    public function __construct(
        private readonly UrlGenerator $url
    ) {
    }

    public function url(string $where): string
    {
        return $this->url->to($where);
    }

    public function getFunctions()
    {
        $functions = [];

        $functions[] = new TwigFunction('url', [$this, 'url']);

        return $functions;
    }
}
