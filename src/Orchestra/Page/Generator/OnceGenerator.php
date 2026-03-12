<?php

namespace Orchestra\Page\Generator;

use Orchestra\Page\Schema;

final class OnceGenerator extends BaseGenerator
{
    public function generate(Schema $schema): iterable
    {
        $contents = [];

        foreach ($schema->contents as $collection) {
            $contents = array_merge($contents, $collection->allByTag());
        }

        yield $this->preparePayload(
            $schema->tag,
            $schema->slug,
            [
                'contents' => $contents
            ],
            $schema
        );
    }
}
