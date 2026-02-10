<?php

namespace Orchestra\Contractor\Builder;

use Orchestra\Pages\Page\Page;

final class SimpleBuilder extends BaseBuilder
{
    public function compile(Page $page): void
    {
        $outputPath = $this->getOutputPath($page->permalink);
        $compiled = $this->twig->render($page->schema->template . '.html', $page->contents);

        file_put_contents($outputPath, $compiled);
    }
}
