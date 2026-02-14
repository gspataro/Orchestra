<?php

namespace Orchestra\Publisher\Builder;

use Orchestra\Pages\Page\Page;

final class TwigBuilder extends BaseBuilder
{
    public function compile(Page $page): void
    {
        $outputPath = $this->getOutputPath($page->permalink);
        $compiled = $this->twig->render($page->schema->template . '.html', (array) $page->contents);

        file_put_contents($outputPath, $compiled);
    }
}
