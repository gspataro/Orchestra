<?php

namespace Orchestra\Theme\Assets;

use Orchestra\Compiler\BuildContext;
use Orchestra\Theme\Theme;

interface DriverInterface
{
    public function build(Theme $theme, BuildContext $context): void;

    /**
     * @return string[]
     */
    public function css(): array;

    /**
     * @return string[]
     */
    public function js(): array;
}
