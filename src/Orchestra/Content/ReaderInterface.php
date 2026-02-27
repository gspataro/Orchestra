<?php

namespace Orchestra\Content;

use Orchestra\Project\Definition\Source\Source;

interface ReaderInterface
{
    /**
     * @param ResolvedSource $source
     * @return iterable<ContentPayload>
     */
    public function compile(Source $source): iterable;
}
