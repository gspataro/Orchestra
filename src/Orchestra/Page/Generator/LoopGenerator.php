<?php

namespace Orchestra\Page\Generator;

use Orchestra\Page\Schema;

final class LoopGenerator extends BaseGenerator
{
    public function generate(Schema $schema): iterable
    {
        $contents = [];

        /** @var \Orchestra\Content\ContentCollection */
        $source = $schema->contents[$schema->source] ?? [];

        foreach ($schema->contents as $group => $collection) {
            if ($group === $schema->source) {
                continue;
            }

            $contents = array_merge($contents, $collection->allByTag());
        }

        foreach ($source as $content) {
            $slug = $content->metadata['slug'] ??  pathinfo($content->path, PATHINFO_FILENAME);

            yield $this->preparePayload(
                $content->id,
                $schema->slug . '/' . $slug,
                [
                    'post' => $content,
                    'contents' => $contents
                ],
                $schema,
                $content->path
            );
        }
    }
}
