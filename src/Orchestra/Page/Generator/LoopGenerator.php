<?php

namespace Orchestra\Page\Generator;

use Orchestra\Page\Schema;

final class LoopGenerator extends BaseGenerator
{
    public function generate(Schema $schema): iterable
    {
        /** @var \Orchestra\Content\ContentCollection */
        $source = $schema->contents[$schema->source] ?? [];

        foreach ($source as $content) {
            $slug = $content->metadata['slug'] ??  pathinfo($content->path, PATHINFO_FILENAME);

            yield $this->preparePayload(
                $content->id,
                $schema->slug . '/' . $slug,
                [
                    'post' => $content,
                    'contents' => $this->additionalContents($schema)
                ],
                $schema,
                $content->path
            );
        }
    }
}
