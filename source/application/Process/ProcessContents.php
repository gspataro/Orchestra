<?php

namespace GSpataro\Application\Process;

use GSpataro\Library\ReadersCollection;
use GSpataro\Project\Prototype;

final class ProcessContents extends Process
{
    public function __construct(
        private Prototype $prototype,
        private ReadersCollection $readers
    ) {
    }

    protected function main(): mixed
    {
        $this->output->print('{bold}Processing contents');

        foreach ($this->prototype->get('contents') as $group => $source) {
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
