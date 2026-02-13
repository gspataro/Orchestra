<?php

namespace Orchestra\Pages\Generator;

use Orchestra\Project\Schema\ResolvedSchema;

final class CollectionGenerator extends BaseGenerator
{
    public function generate(ResolvedSchema $schema): void
    {
        $contents = $schema->contents;

        /** @var \Orchestra\Content\ContentCollection */
        $source = $contents[$schema->source] ?? [];

        /** @var \Orchestra\Content\ContentCollection */
        $relationship = $contents[$schema->options['relationship']] ?? [];

        unset($contents[$schema->source], $contents[$schema->options['relationship']]);

        foreach ($source as $collection) {
            $relationshipContents = $relationship->query()
                ->where('metadata.categories', 'containsAny', [$collection->get('body.slug')]);
            $perPage = $schema->options['per_page'] ?? 12;
            $totalPages = ceil($relationshipContents->count() / $perPage);
            $pages = $relationshipContents->paginate($perPage);

            for ($i = 0; $i < count($pages); $i++) {
                $currentPage = $i + 1;
                $currentSlug = $currentPage > 1 ? $currentPage : 'index';

                $this->createPage(
                    $schema->tag,
                    $this->sitemap->add(
                        $schema->tag . '.' . $collection->get('body.slug') . '.page-' . $currentPage,
                        $schema->slug . '/' . $collection->get('body.slug') . '/' . $currentSlug
                    ),
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

        /*$pages = $source->query()->paginate($perPage);

        for ($i = 0; $i < count($pages); $i++) {
            $currentPage = $i + 1;
            $currentSlug = $currentPage > 1 ? $currentPage : 'index';

            $this->createPage(
                $schema->tag,
                $this->sitemap->add(
                    $schema->tag . '.page-' . $currentPage,
                    $schema->slug . '/' . $currentSlug
                ),
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
        }*/
    }
}
