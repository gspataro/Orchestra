<?php

namespace GSpataro\Pages\Generator;

final class PaginateGenerator extends BaseGenerator
{
    /**
     * Generate pages based on schema
     *
     * @param array $schema
     * @return void
     */

    public function generate(array $schema): void
    {
        $contents = $schema['contents'];
        $basedOn = $contents[$schema['generate_based_on']];

        if (empty($basedOn)) {
            return;
        }

        unset($contents[$schema['generate_based_on']]);

        $perPage = $schema['options']['per_page'] ?? 12;
        $totalPages = ceil(count($basedOn) / $perPage);

        $this->createCollection(
            $schema['tag'],
            $schema['template'],
            $schema['builder'],
            $contents
        );

        for ($i = 0; $i < $totalPages; $i++) {
            $currentPage = $i + 1;
            $currentSlug = $currentPage > 1 ? $currentPage : 'index';
            $slice = array_slice($basedOn, $i * $perPage, $perPage);

            $this->addPageToCollection(
                $schema['tag'],
                'page-' . $currentPage,
                $this->sitemap->add(
                    $schema['tag'] . '.page-' . $currentPage,
                    $schema['slug'] . '/' . $currentSlug
                ),
                [
                    $schema['tag'] => $slice,
                    'pagination' => [
                        'current' => $currentPage,
                        'next' => $currentPage < $totalPages ? $currentPage + 1 : null,
                        'prev' => $currentPage > 1 ? $currentPage - 1 : null
                    ]
                ]
            );
        }
    }
}
