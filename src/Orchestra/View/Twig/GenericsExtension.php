<?php

namespace Orchestra\View\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class GenericsExtension extends AbstractExtension
{
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
}
