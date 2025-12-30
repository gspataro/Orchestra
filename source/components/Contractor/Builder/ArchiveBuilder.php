<?php

namespace GSpataro\Contractor\Builder;

final class ArchiveBuilder extends BaseBuilder
{
    /**
     * Compile archive page
     *
     * @param array $page
     * @return mixed
     */

    public function compile(array $page): void
    {
        if (!$page['collection']) {
            return;
        }

        foreach ($page['collection'] as $archive) {
            $outputPath = $this->getOutputPath($archive['permalink']);
            $compiled = $this->twig->render(
                $page['template'] . '.html',
                array_merge($page['contents'], $archive['contents'])
            );

            file_put_contents($outputPath, $compiled);
        }
    }
}
