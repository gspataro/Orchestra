<?php

namespace GSpataro\Contractor\Builder;

final class SimpleBuilder extends BaseBuilder
{
    /**
     * Compile a page
     *
     * @param array $page
     * @return mixed
     */

    public function compile(array $page): void
    {
        $outputPath = $this->getOutputPath($page['permalink']);
        $compiled = $this->twig->render($page['template'] . '.html', $page['contents']);

        file_put_contents($outputPath, $compiled);
    }
}
