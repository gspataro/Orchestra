<?php

namespace Orchestra\Project\Interpreter;

use Orchestra\Project\Blueprint;
use Orchestra\Project\CompilerContext;
use Orchestra\Project\InterpreterInterface;

final class ConfigInterpreter implements InterpreterInterface
{
    public function compile(Blueprint $blueprint, CompilerContext $context): void
    {
        $website = $blueprint->get('website') ?? [];

        $website['name'] ??= 'Solista';
        $website['description'] ??= 'PHP static website builder';
        $website['theme'] ??= 'pianoforte';
        $website['friendly_urls'] ??= true;

        $context->configs->set('website', $website);
    }
}
