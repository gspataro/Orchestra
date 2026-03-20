<?php

namespace Orchestra\Publisher\Builder;

use Orchestra\Page\Page;
use Orchestra\Publisher\BuilderInterface;
use Twig\Environment;

final class TwigBuilder implements BuilderInterface
{
    public function __construct(
        private readonly Environment $twig
    ) {
    }

    public function compile(Page $page): string
    {
        return $this->twig->render($page->schema->template . '.twig', (array) $page->contents);
    }
}
