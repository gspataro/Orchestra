<?php

namespace Orchestra\Test\Stubs;

use Orchestra\Utilities\DotNavigator;

class DotNavigatorStub extends DotNavigator
{
    public function __construct(array $data = [], bool $readOnly = false)
    {
        $this->readOnly = $readOnly;

        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function expose(array $data): void
    {
        $this->fill($data);
    }
}
