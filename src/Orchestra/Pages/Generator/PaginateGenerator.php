<?php

namespace Orchestra\Pages\Generator;

use Orchestra\Project\Schema\ResolvedSchema;

final class PaginateGenerator extends BaseGenerator
{
    public function generate(ResolvedSchema $schema): void
    {
        $contents = $schema->contents;
        $source = $contents[$schema->source] ?? [];

        if (empty($source)) {
            $this->createPage(
                $schema->tag,
                $this->sitemap->add(
                    $schema->tag . '.page-1',
                    $schema->slug . '/index'
                ),
                [],
                $schema
            );
            return;
        }

        unset($contents[$schema->source]);

        $perPage = $schema->options['per_page'] ?? 12;
        $totalPages = ceil(count($source) / $perPage);

        for ($i = 0; $i < $totalPages; $i++) {
            $currentPage = $i + 1;
            $currentSlug = $currentPage > 1 ? $currentPage : 'index';
            $slice = array_slice($source, $i * $perPage, $perPage);

            $this->createPage(
                $schema->tag,
                $this->sitemap->add(
                    $schema->tag . '.page-' . $currentPage,
                    $schema->slug . '/' . $currentSlug
                ),
                [
                    $schema->tag => $slice,
                    'pagination' => [
                        'next' => $currentPage < $totalPages ? $currentPage + 1 : null,
                        'prev' => $currentPage > 1 ? $currentPage - 1 : null
                    ]
                ],
                $schema
            );
        }
    }
}
