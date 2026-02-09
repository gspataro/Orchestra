<?php

namespace Orchestra\Console\Runtime;

use GSpataro\CLI\Interface\InputInterface;
use GSpataro\CLI\Interface\OutputInterface;

abstract class Runtime
{
    protected InputInterface $input;
    protected OutputInterface $output;

    abstract protected function main(): mixed;

    public function run(InputInterface $input, OutputInterface $output): mixed
    {
        $this->input = $input;
        $this->output = $output;

        return $this->main();
    }
}
