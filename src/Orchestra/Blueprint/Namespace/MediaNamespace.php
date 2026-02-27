<?php

namespace Orchestra\Blueprint\Namespace;

use Orchestra\Blueprint\NamespaceInterface;
use Orchestra\Utilities\DotNavigator;

final class MediaNamespace extends DotNavigator implements NamespaceInterface
{
    protected bool $readOnly = true;

    public function __construct(array $data)
    {
        $this->fill($data);
    }
}
