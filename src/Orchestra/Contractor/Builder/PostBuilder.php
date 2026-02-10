<?php

namespace Orchestra\Contractor\Builder;

use Orchestra\Pages\Page\Page;

final class PostBuilder extends BaseBuilder
{
    public function compile(Page $page): void
    {
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
