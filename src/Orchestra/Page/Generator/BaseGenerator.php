<?php

namespace Orchestra\Page\Generator;

use Orchestra\Page\GeneratorInterface;
use Orchestra\Page\PagePayload;
use Orchestra\Page\Schema;

abstract class BaseGenerator implements GeneratorInterface
{
    /**
     * @param string $tag
     * @param string $permalink
     * @param array<string|int,mixed> $contents
     * @param Schema $schema
     * @return PagePayload
     */
    protected function preparePayload(
        string $tag,
        string $permalink,
        array $contents,
        Schema $schema
    ): PagePayload {
        return new PagePayload(
            $tag,
            $permalink,
            $contents,
            $schema
        );
    }
}
