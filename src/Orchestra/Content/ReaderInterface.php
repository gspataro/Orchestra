<?php

namespace Orchestra\Content;

use Orchestra\Project\Source\ResolvedSource;

interface ReaderInterface
{
    /**
     * @param ResolvedSource|ResolvedSource[] $source
     * @return Content|Content[]
     */
    public function compile(ResolvedSource|array $source): Content|array;
}
