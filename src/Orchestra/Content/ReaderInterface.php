<?php

namespace Orchestra\Content;

use Orchestra\Project\Source\ResolvedSource;

interface ReaderInterface
{
    /**
     * @param ResolvedSource $source
     * @return ContentPayload|ContentPayload[]
     */
    public function compile(ResolvedSource $source): ContentPayload|array;
}
