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

        foreach ($source as $contentTag => $content) {
            $tag = $schema->tag . '.' . $contentTag;
            $content['id'] = $contentTag;

            $this->createPage(
                $schema->tag,
                $this->sitemap->add($tag, $schema->slug . '/' . $contentTag),
                [$schema->tag => $content],
                $schema
            );
        }
    }
}
