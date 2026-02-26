<?php

namespace Orchestra\Project;

interface InterpreterInterface
{
    public function compile(Blueprint $blueprint, CompilerContext $context): void;
}
