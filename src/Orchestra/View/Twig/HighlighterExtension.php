<?php

namespace Orchestra\View\Twig;

use Twig\TwigFilter;
use Tempest\Highlight\Highlighter;
use Twig\Extension\AbstractExtension;

final class HighlighterExtension extends AbstractExtension
{
    public function __construct(
        private readonly Highlighter $highlighter,
    ) {
    }

    public function highlight($code, $language)
    {
        return $this->highlighter->parse($code, $language);
    }

    public function getFilters()
    {
        $filters = [];

        $filters[] = new TwigFilter('highlight', [$this, 'highlight'], [
            'is_safe' => ['html']
        ]);

        return $filters;
    }
}
