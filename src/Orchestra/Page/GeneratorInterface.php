<?php

namespace Orchestra\Page;

use Orchestra\Project\Definition\Schema\Schema;

interface GeneratorInterface
{
    /**
     * @param Schema $schema
     * @return iterable<PagePayload>
     */
    public function generate(Schema $schema): iterable;
}
