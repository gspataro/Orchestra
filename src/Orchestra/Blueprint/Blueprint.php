<?php

namespace Orchestra\Blueprint;

use Orchestra\Utilities\DotNavigator;

final class Blueprint extends DotNavigator
{
    protected bool $readOnly = true;

    public function init(array $data)
    {
        $this->fill($data);
    }
}
