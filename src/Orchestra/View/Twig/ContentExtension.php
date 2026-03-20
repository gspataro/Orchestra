<?php

namespace Orchestra\View\Twig;

use Orchestra\Content\Content;
use Orchestra\Content\ContentCollection;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class ContentExtension extends AbstractExtension
{
    /**
     * @param array<string,mixed> $context
     * @param string $group
     * @param Content|null $content
     * @return ContentCollection
     */
    public function related(array $context, string $group, ?Content $content = null): ContentCollection
    {
        $content ??= $context['post'] ?? null;

        if (!$content) {
            return new ContentCollection([]);
        }

        return $content->get("relationships.{$group}") ?? new ContentCollection([]);
    }

    /**
     * @param array<string,mixed> $context
     * @param string $group
     * @return mixed
     */
    public function content(array $context, string $group): mixed
    {
        return $context['contents'][$group] ?? [];
    }

    public function getFunctions()
    {
        $functions = [];

        $functions[] = new TwigFunction('related', [$this, 'related'], [
            'needs_context' => true
        ]);

        $functions[] = new TwigFunction('content', [$this, 'content'], [
            'needs_context' => true
        ]);

        return $functions;
    }
}
