<?php

namespace Orchestra\Pages;

use Orchestra\Project\Schema\ResolvedSchema;

interface GeneratorInterface
{
    /**
     * @param ResolvedSchema $schema
     * @return iterable<PagePayload>
     */
    public function generate(ResolvedSchema $schema): iterable;
}
