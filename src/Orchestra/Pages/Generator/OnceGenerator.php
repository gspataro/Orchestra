<?php

namespace Orchestra\Pages\Generator;

use Orchestra\Project\Schema\ResolvedSchema;

final class OnceGenerator extends BaseGenerator
{
    public function generate(ResolvedSchema $schema): iterable
    {
        yield $this->createPage(
            $schema->tag,
            $this->sitemap->add($schema->tag, $schema->slug),
            $schema->contents,
            $schema
        );
    }
}
