<?php

namespace Orchestra\Pages\Generator;

use Orchestra\Project\Schema\ResolvedSchema;

final class LoopGenerator extends BaseGenerator
{
    public function generate(ResolvedSchema $schema): iterable
    {
        $contents = $schema->contents;
        $source = $contents[$schema->source] ?? [];

        if (!empty($source)) {
            unset($contents[$schema->source]);
        }

        foreach ($source as $content) {
            $contentTag = pathinfo($content->path, PATHINFO_FILENAME);

            yield $this->createPage(
                $schema->tag,
                $this->sitemap->add($content->id, $schema->slug . '/' . $contentTag),
                ['post' => $content],
                $schema
            );
        }
    }
}
