<?php

namespace Orchestra\Pages\Generator;

use Orchestra\Project\Schema\ResolvedSchema;

final class OnceGenerator extends BaseGenerator
{
    public function generate(ResolvedSchema $schema): iterable
    {
        yield $this->preparePayload(
            $schema->tag,
            $schema->slug,
            $schema->contents,
            $schema
        );
    }
}
