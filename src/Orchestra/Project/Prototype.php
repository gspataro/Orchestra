<?php

namespace Orchestra\Project;

use Orchestra\Project\Content\ContentCollection;
use Orchestra\Project\Schema\SchemaCollection;
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
        private ContentCollection $contents,
        private SchemaCollection $schemas
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
