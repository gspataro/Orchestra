<?php

namespace Orchestra\Publisher;

use Orchestra\Page\Page;
use Orchestra\View\RendererInterface;
use Orchestra\View\TemplateResolver;

final class Builder
{
    public function __construct(
        private readonly TemplateResolver $resolver,
        private readonly RendererInterface $renderer
    ) {
    }

    public function build(Page $page): string
    {
        $template = is_null($page->schema->template) ? $this->resolver->resolve($page) : $page->schema->template;
        return $this->renderer->render($template, $page->contents);
    }
}
