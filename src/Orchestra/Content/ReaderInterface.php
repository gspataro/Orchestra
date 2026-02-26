<?php

namespace Orchestra\Content;

use Orchestra\Project\Definition\Source\ResolvedSource;

interface ReaderInterface
{
    /**
     * @param ResolvedSource $source
     * @return iterable<ContentPayload>
     */
    public function compile(ResolvedSource $source): iterable;
}
