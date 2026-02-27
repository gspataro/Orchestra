<?php

namespace Orchestra\Project;

use Orchestra\Blueprint\Blueprint;

interface InterpreterInterface
{
    public function compile(Blueprint $blueprint, CompilerContext $context): void;
}
