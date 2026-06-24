<?php

namespace Orchestra\View;

use Orchestra\Page\Page;

final class TemplateResolver
{
    /** @var string[] */
    private array $hierarchy = [
        '{type}-{schema}',
        '{type}'
    ];

    public function __construct(
        private readonly RendererInterface $renderer
    ) {
    }

    public function resolve(Page $page): string
    {
        foreach ($this->hierarchy as $pattern) {
            $type = match ($page->schema->generator) {
                'once' => 'page',
                'loop' => 'single',
                default => $page->schema->generator
            };

            $template = str_replace(
                [
                    '{type}',
                    '{schema}'
                ],
                [
                    $type,
                    $page->schema->tag
                ],
                $pattern
            );

            if ($this->renderer->templateExists($template)) {
                return $template;
            }
        }

        return 'default';
    }
}
