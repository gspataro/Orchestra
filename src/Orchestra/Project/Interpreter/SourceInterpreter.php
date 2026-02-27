<?php

namespace Orchestra\Project\Interpreter;

use Orchestra\Blueprint\Blueprint;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\Definition\Source\Source;
use Orchestra\Project\Exception\InvalidBlueprintException;
use Orchestra\Project\InterpreterInterface;

final class SourceInterpreter implements InterpreterInterface
{
    public function compile(Blueprint $blueprint, CompilerContext $context): void
    {
        $sources = $blueprint->get('contents') ?? [];

        if (empty($sources)) {
            return;
        }

        foreach ($sources as $group => $source) {
            if (!str_contains($source, ':')) {
                throw new InvalidBlueprintException(
                    "Invalid data source for group '{$group}'. A data source must be in the format of 'reader:path'."
                );
            }

            [$reader, $path] = explode(':', $source, 2);

            $context->sources->add(new Source($group, $reader, $path));
        }
    }
}
