<?php

namespace GSpataro\Contractor\Builder;

final class PostBuilder extends BaseBuilder
{
    /**
     * Compile post
     *
     * @param array $page
     * @return mixed
     */

    public function compile(array $page): void
    {
        if (!$page['collection']) {
            return;
        }

        foreach ($page['collection'] as $post) {
            $outputPath = $this->getOutputPath($post['permalink']);
            $compiled = $this->twig->render(
                $page['template'] . '.html',
                array_merge($page['contents'], $post['contents'])
            );

            file_put_contents($outputPath, $compiled);
        }
    }
}
