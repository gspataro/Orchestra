<?php

namespace Orchestra\Page\Generator;

use Orchestra\Page\GeneratorInterface;
use Orchestra\Page\PagePayload;
use Orchestra\Page\Schema;

abstract class BaseGenerator implements GeneratorInterface
{
    /**
     * @param Schema $schema
     * @return array<string,mixed>
     */
    protected function additionalContents(Schema $schema): array
    {
        $contents = [];

        foreach ($schema->contents as $group => $collection) {
            if ($group === $schema->source) {
                continue;
            }

            $byTag = $collection->allByTag();
            $contents[$group] = count($collection) > 1 ? $byTag : $byTag[array_key_first($byTag)];
        }

        return $contents;
    }

    /**
     * @param string $tag
     * @param string $permalink
     * @param array<string|int,mixed> $contents
     * @param Schema $schema
     * @param string|null $sourcePath
     * @return PagePayload
     */
    protected function preparePayload(
        string $tag,
        string $permalink,
        array $contents,
        Schema $schema,
        ?string $sourcePath = null
    ): PagePayload {
        return new PagePayload(
            $tag,
            $permalink,
            $contents,
            $schema,
            $sourcePath
        );
    }
}
