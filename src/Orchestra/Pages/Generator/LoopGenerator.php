<?php

namespace Orchestra\Pages\Generator;

use Orchestra\Project\Schema\ResolvedSchema;

final class LoopGenerator extends BaseGenerator
{
    public function generate(ResolvedSchema $schema): void
    {
        $contents = $schema->contents;
        $source = $contents[$schema->source] ?? [];

        if (empty($source)) {
            return;
        }

        unset($contents[$schema->source]);

        foreach ($source as $content) {
            $contentTag = pathinfo($content->path, PATHINFO_FILENAME);

            $this->createPage(
                $schema->tag,
                $this->sitemap->add($content->id, $schema->slug . '/' . $contentTag),
                ['post' => $content],
                $schema
            );
        }
    }
}
