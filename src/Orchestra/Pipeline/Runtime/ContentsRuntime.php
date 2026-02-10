<?php

namespace Orchestra\Pipeline\Runtime;

use Orchestra\Content\ReadersCollection;

final class ContentsRuntime extends Runtime
{
    private readonly ReadersCollection $readers;

    public function run(array $options = []): bool
    {
        $this->readers = $this->container->get('content.readers');

        $this->output->info('Processing contents');

        foreach ($this->context->prototype->getSources() as $source) {
            $this->output->print("Working on content group '{$source->group}'");

            $reader = $this->readers->get($source->reader);
            $reader->compile($source->group, $source->path);
        }

        return true;
    }
}
