<?php

namespace Orchestra\Compiler;

interface PipelineInterface
{
    public function setOutputAdapter(BuildOutputInterface $output): self;
    public function run(BuildOptions $options): bool;
}
