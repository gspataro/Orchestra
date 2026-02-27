<?php

namespace Orchestra\Project\Interpreter;

use Orchestra\Blueprint\NamespaceInterface;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\InterpreterInterface;

final class ConfigInterpreter implements InterpreterInterface
{
    public function namespace(): string
    {
        return 'website';
    }

    public function compile(NamespaceInterface $config, CompilerContext $context): void
    {
        $context->configs->set('website', $config->all());
    }
}
