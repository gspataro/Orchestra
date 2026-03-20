<?php

namespace Orchestra\Blueprint;

use Orchestra\Utilities\DotNavigator;

final class Blueprint extends DotNavigator
{
    protected bool $readOnly = true;

    /**
     * @param array<mixed,mixed> $data
     * @return void
     */
    public function init(array $data): void
    {
        $this->fill($data);
    }
}
