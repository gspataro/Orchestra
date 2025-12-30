<?php

namespace GSpataro\View;

use GSpataro\Assets\Vite;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class TwigGenerics extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly Vite $vite
    ) {
    }

    public function pregMatch($subject, $pattern)
    {
        $matches = [];
        preg_match($pattern, $subject, $matches);

        return $matches;
    }

    public function pregReplace($subject, $pattern, $replacement)
    {
        return preg_replace($pattern, $replacement, $subject);
    }

    public function getFilters()
    {
        $filters = [];

        $filters[] = new TwigFilter('preg_match', [$this, 'pregMatch']);
        $filters[] = new TwigFilter('preg_replace', [$this, 'pregReplace']);

        return $filters;
    }

    public function getGlobals(): array
    {
        return [
            'vite' => $this->vite
        ];
    }
}
