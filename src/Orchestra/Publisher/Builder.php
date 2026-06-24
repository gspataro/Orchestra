<?php

namespace Orchestra\Publisher;

use Orchestra\Page\Page;
use Orchestra\View\RendererInterface;

final class Builder
{
    public function __construct(
        private readonly RendererInterface $renderer
    ) {
    }

    public function build(Page $page): string
    {
        return $this->renderer->render($page->schema->template, $page->contents);
    }
}
