<?php

namespace Orchestra\Theme\Assets;

use Orchestra\Compiler\BuildContext;
use Orchestra\Theme\Theme;

interface DriverInterface
{
    public function build(Theme $theme, BuildContext $context): void;
    public function css(): array;
    public function js(): array;
}
