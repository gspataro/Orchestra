<?php

namespace Orchestra\Compiler\Factory;

use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\Paths;

final class BuildContextFactory
{
    public function make(?Paths $paths = null): BuildContext
    {
        return new BuildContext($paths);
    }
}
