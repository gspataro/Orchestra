<?php

namespace Orchestra\Pages\Generator;

use Orchestra\Project\Schema\ResolvedSchema;

final class LoopGenerator extends BaseGenerator
{
    public function generate(ResolvedSchema $schema): void
    {
        $contents = $schema->contents;
        $basedOn = $this->archive->get($schema->source);

        if (empty($basedOn)) {
            return;
        }

        unset($contents[$schema->source]);

        $this->createCollection(
            $schema->tag,
            $schema->template,
            $schema->builder,
            $contents
        );

        foreach ($basedOn as $contentTag => $content) {
            $tag = $schema->tag . '.' . $contentTag;
            $content['id'] = $contentTag;

            $this->addPageToCollection(
                $schema->tag,
                $contentTag,
                $this->sitemap->add($tag, $schema->slug . '/' . $contentTag),
                [$schema->tag => $content]
            );
        }
    }
}
