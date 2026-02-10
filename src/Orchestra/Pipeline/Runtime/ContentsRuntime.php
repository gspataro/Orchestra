<?php

namespace Orchestra\Pipeline\Runtime;

use Orchestra\Library\ReadersCollection;

final class ContentsRuntime extends Runtime
{
    private readonly ReadersCollection $readers;

    public function run(array $options = []): bool
    {
        $this->readers = $this->container->get('library.readers');

        $this->output->info('Processing contents');

        foreach ($this->context->prototype->getSources() as $source) {
            $this->output->print("Working on content group '{$source->group}'");

            $reader = $this->readers->get($source->reader);
            $reader->compile($source->group, $source->path);

            if ($reader->failed()) {
                $error = $reader->getError();
                $this->output->error("Contents processing failed on group '{$source->group}'.");
                $this->output->print('Error: {clear}' . $error->value);
                $this->output->print('Source: {clear}' . $reader->getFailedSource());
                exit(1);
            }
        }

        return true;
    }
}
