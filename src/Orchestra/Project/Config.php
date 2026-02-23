<?php

namespace Orchestra\Project;

use Orchestra\Utilities\DotNavigator;

final class Config extends DotNavigator
{
    protected bool $readOnly = true;

    public function __construct(
        array $configs
    ) {
        $this->fill($configs);
    }
}
