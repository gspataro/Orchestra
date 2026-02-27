<?php

namespace Orchestra\Page\Generator;

use Orchestra\Project\Definition\Schema\Schema;

final class OnceGenerator extends BaseGenerator
{
    public function generate(Schema $schema): iterable
    {
        yield $this->preparePayload(
            $schema->tag,
            $schema->slug,
            $schema->contents,
            $schema
        );
    }
}
