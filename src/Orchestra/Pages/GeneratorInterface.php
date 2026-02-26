<?php

namespace Orchestra\Pages;

use Orchestra\Project\Schema\ResolvedSchema;

interface GeneratorInterface
{
    public function generate(ResolvedSchema $schema): void;
}
