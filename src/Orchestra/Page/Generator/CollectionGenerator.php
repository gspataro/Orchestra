<?php

namespace Orchestra\Page\Generator;

use Orchestra\Page\Schema;

final class CollectionGenerator extends BaseGenerator
{
    public function generate(Schema $schema): iterable
    {
        $contents = $schema->contents;
        $fillWith = $schema->options['fillWith'];

        /** @var \Orchestra\Content\ContentCollection */
        $source = $contents[$schema->source] ?? [];

        foreach ($source as $collection) {
            $relationshipContents = $collection->get("metadata.{$fillWith}")->query();

            $perPage = $schema->options['per_page'] ?? 12;
            $totalPages = ceil($relationshipContents->count() / $perPage);
            $pages = $relationshipContents->paginate($perPage);

            if (empty($pages)) {
                $pages = [0 => []];
            }

            for ($i = 0; $i < count($pages); $i++) {
                $currentPage = $i + 1;
                $currentSlug = $currentPage > 1 ? $currentPage : 'index';
                $collectionSlug = $collection->metadata['slug'] ?? pathinfo($collection->path, PATHINFO_FILENAME);

                yield $this->preparePayload(
                    $schema->tag . '.' . $collectionSlug . '.page-' . $currentPage,
                    $schema->slug . '/' . $collectionSlug . '/' . $currentSlug,
                    [
                        'archive' => [
                            'loop' => $pages[$i],
                            'pagination' => [
                                'next' => $currentPage < $totalPages ? $currentPage + 1 : null,
                                'prev' => $currentPage > 1 ? $currentPage - 1 : null
                            ]
                        ]
                    ],
                    $schema
                );
            }
        }
    }
}
