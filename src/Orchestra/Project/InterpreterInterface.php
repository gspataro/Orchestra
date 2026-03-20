<?php

namespace Orchestra\Project;

use Orchestra\Blueprint\NamespaceInterface;

interface InterpreterInterface
{
    public function namespace(): string;
    public function compile(NamespaceInterface $namespace, CompilerContext $context): void;
}
