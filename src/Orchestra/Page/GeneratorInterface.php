<?php

namespace Orchestra\Page;

use Orchestra\Page\Schema;

interface GeneratorInterface
{
    /**
     * @param Schema $schema
     * @return iterable<PagePayload>
     */
    public function generate(Schema $schema): iterable;
}
