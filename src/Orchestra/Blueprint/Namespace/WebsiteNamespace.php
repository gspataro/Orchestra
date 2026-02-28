<?php

namespace Orchestra\Blueprint\Namespace;

use Orchestra\Blueprint\NamespaceInterface;
use Orchestra\Utilities\DotNavigator;

final class WebsiteNamespace extends DotNavigator implements NamespaceInterface
{
    protected bool $readOnly = true;

    /**
     * @param array<string|int,mixed> $data
     */
    public function __construct(array $data)
    {
        $this->fill($data);
    }
}
