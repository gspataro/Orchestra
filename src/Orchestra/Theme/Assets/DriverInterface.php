<?php

namespace Orchestra\Theme\Assets;

use Orchestra\Compiler\BuildContext;
use Orchestra\Theme\Theme;

interface DriverInterface
{
    public function discover(Theme $theme): void;

    /**
     * @return AssetEntry[]
     */
    public function entries(): array;
}
