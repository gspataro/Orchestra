<?php

namespace Orchestra\View\Twig;

use Orchestra\Content\Content;
use Orchestra\Content\ContentCollection;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class ContentExtension extends AbstractExtension
{
    public function related(array $context, string $group, ?Content $content = null): ContentCollection
    {
        $content ??= $context['post'] ?? null;

        if (!$content) {
            return new ContentCollection([]);
        }

        return $content->get("relationships.{$group}") ?? new ContentCollection([]);
    }

    public function getFunctions()
    {
        $functions = [];

        $functions[] = new TwigFunction('related', [$this, 'related'], [
            'needs_context' => true
        ]);

        return $functions;
    }
}
