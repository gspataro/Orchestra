<?php

namespace Orchestra\Pages\Interface;

use Orchestra\Project\Schema\ResolvedSchema;

interface GeneratorInterface
{
    public function generate(ResolvedSchema $schema): void;
}
