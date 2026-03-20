<?php

namespace Orchestra\Compiler\Factory;

use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\Paths;

final class BuildContextFactory
{
    public function make(?Paths $paths = null): BuildContext
    {
        if (is_null($paths)) {
            $paths = Paths::builder(getcwd())->build();
        }

        return new BuildContext($paths);
    }
}
