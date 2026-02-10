<?php

namespace Orchestra\Console\Runtime;

use Orchestra\Library\ReadersCollection;
use Orchestra\Pipeline\BuildContext;

final class ContentsRuntime extends Runtime
{
    private readonly ReadersCollection $readers;

    protected function main(): mixed
    {
        $this->readers = $this->container->get('library.readers');

        $this->output->print('{bold}Processing contents');

        foreach ($this->context->prototype->get('contents') as $content) {
            $this->output->print("Working on content group '{$content->group}'");

            $reader = $this->readers->get($content->reader);
            $reader->compile($content->group, $content->path);

            if ($reader->failed()) {
                $error = $reader->getError();
                $this->output->print("{bold}{fg_red}Contents processing failed on group '{$content->group}'.");
                $this->output->print('{bold}Error: {clear}' . $error->value);
                $this->output->print('{bold}Source: {clear}' . $reader->getFailedSource());
                exit(1);
            }
        }

        return true;
    }
}
