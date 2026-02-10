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

        foreach ($this->context->prototype->get('contents') as $content) {
            $this->output->print("Working on content group '{$content->group}'");

            $reader = $this->readers->get($content->reader);
            $reader->compile($content->group, $content->path);

            if ($reader->failed()) {
                $error = $reader->getError();
                $this->output->error("Contents processing failed on group '{$content->group}'.");
                $this->output->print('Error: {clear}' . $error->value);
                $this->output->print('Source: {clear}' . $reader->getFailedSource());
                exit(1);
            }
        }

        return true;
    }
}
