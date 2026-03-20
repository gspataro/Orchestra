<?php

namespace Orchestra\Compiler;

interface PipelineInterface
{
    public function run(BuildOptions $options): bool;
}
