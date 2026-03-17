<?php

namespace Orchestra\Project\Interpreter;

use Orchestra\Blueprint\NamespaceInterface;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\InterpreterInterface;

final class OrchestraInterpreter implements InterpreterInterface
{
    public function namespace(): string
    {
        return 'orchestra';
    }

    public function compile(NamespaceInterface $config, CompilerContext $context): void
    {
        $context->configs->set('orchestra', $config->all());
    }
}
