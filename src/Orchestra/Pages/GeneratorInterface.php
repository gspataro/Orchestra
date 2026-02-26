<?php

namespace Orchestra\Pages;

use Orchestra\Project\Schema\ResolvedSchema;

interface GeneratorInterface
{
    /**
     * @param ResolvedSchema $schema
     * @return iterable<Page>
     */
    public function generate(ResolvedSchema $schema): iterable;
}
