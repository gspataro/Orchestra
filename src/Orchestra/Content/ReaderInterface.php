<?php

namespace Orchestra\Content;

use Orchestra\Project\Source\ResolvedSource;

interface ReaderInterface
{
    /**
     * @param ResolvedSource|ResolvedSource[] $source
     * @return ContentPayload|ContentPayload[]
     */
    public function compile(ResolvedSource|array $source): ContentPayload|array;
}
