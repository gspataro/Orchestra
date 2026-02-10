<?php

namespace Orchestra\Project;

use Orchestra\Utilities\DotNavigator;

final class Prototype extends DotNavigator
{
    protected bool $readOnly = true;

    /**
     * Initialize prototype
     *
     * @param Blueprint $blueprint
     */

    public function __construct(
        private array $contents,
        private array $schemas
    ) {
        $this->fill([
            'contents' => $contents,
            'schemas' => $schemas
        ]);
    }

    public function getAll()
    {
        return $this->data;
    }
}
