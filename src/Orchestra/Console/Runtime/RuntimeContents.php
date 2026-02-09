<?php

namespace Orchestra\Console\Runtime;

use Orchestra\Library\ReadersCollection;
use Orchestra\Pipeline\BuildContext;

final class RuntimeContents extends Runtime
{
    public function __construct(
        private BuildContext $context,
        private ReadersCollection $readers
    ) {
    }

    protected function main(): mixed
    {
        $this->output->print('{bold}Processing contents');

        foreach ($this->context->prototype->get('contents') as $group => $source) {
            $this->output->print("Working on content group '{$group}'");

            $reader = $this->readers->get($source['reader']);
            $contents[$group] = $reader->compile($group, $source['path']);

            if ($reader->failed()) {
                $error = $reader->getError();
                $this->output->print("{bold}{fg_red}Contents processing failed on group '{$group}'.");
                $this->output->print('{bold}Error: {clear}' . $error->value);
                $this->output->print('{bold}Source: {clear}' . $reader->getFailedSource());
                exit(1);
            }
        }

        return true;
    }
}
