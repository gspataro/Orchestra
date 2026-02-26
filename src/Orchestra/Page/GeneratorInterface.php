<?php

namespace Orchestra\Page;

use Orchestra\Project\Definition\Schema\ResolvedSchema;

interface GeneratorInterface
{
    /**
     * @param ResolvedSchema $schema
     * @return iterable<PagePayload>
     */
    public function generate(ResolvedSchema $schema): iterable;
}
