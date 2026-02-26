<?php

namespace Orchestra\Pages\Generator;

use Orchestra\Project\Schema\ResolvedSchema;

final class ArchiveGenerator extends BaseGenerator
{
    public function generate(ResolvedSchema $schema): iterable
    {
        $contents = $schema->contents;

        /** @var \Orchestra\Content\ContentCollection */
        $source = $contents[$schema->source] ?? [];

        $perPage = $schema->options['per_page'] ?? 12;
        $totalPages = ceil(count($source) / $perPage);
        $pages = $source->query()->paginate($perPage);

        if (empty($pages)) {
            $pages = [0 => []];
        }

        for ($i = 0; $i < count($pages); $i++) {
            $currentPage = $i + 1;
            $currentSlug = $currentPage > 1 ? $currentPage : 'index';

            yield $this->preparePayload(
                $schema->tag . '.page-' . $currentPage,
                $schema->slug . '/' . $currentSlug,
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
