<?php

namespace Orchestra\Compiler;

interface RuntimeInterface
{
    public function run(BuildOptions $options): bool;
}
