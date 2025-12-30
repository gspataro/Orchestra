<?php

namespace GSpataro\Application\Process;

use GSpataro\Contractor\BuildersCollection;
use GSpataro\Pages\Pages;

class ProcessPages extends Process
{
    public function __construct(
        private Pages $pages,
        private BuildersCollection $builders
    ) {
    }

    protected function main(): mixed
    {
        $this->output->print('{bold}Building pages');

        foreach ($this->pages->getAll() as $page) {
            $builder = $this->builders->get($page['builder']);
            $builder->compile($page);
        }

        return true;
    }
}
