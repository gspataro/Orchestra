<?php

namespace Orchestra\Project\Interpreter;

use Orchestra\Blueprint\NamespaceInterface;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\Definition\Source\SourceDefinition;
use Orchestra\Project\InterpreterInterface;

final class SourceInterpreter implements InterpreterInterface
{
    public function namespace(): string
    {
        return 'contents';
    }

    public function compile(NamespaceInterface $sources, CompilerContext $context): void
    {
        $sources = $sources->all();

        if (empty($sources)) {
            return;
        }

        foreach ($sources as $group => $source) {
            $context->sources->add(new SourceDefinition(
                $group,
                $source['reader'],
                $source['files'],
                $source['relationships']
            ));
        }
    }
}
