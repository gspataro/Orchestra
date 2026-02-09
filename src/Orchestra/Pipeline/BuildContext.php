<?php

namespace Orchestra\Pipeline;

use Orchestra\Project\Blueprint;
use Orchestra\Project\Prototype;
use Orchestra\Project\Sitemap;

final class BuildContext
{
    public readonly Blueprint $blueprint;
    public readonly Prototype $prototype;
    public readonly Sitemap $sitemap;

    public function __construct(
        public readonly string $root
    ) {
    }

    public function setContext(Blueprint $blueprint, Prototype $prototype, Sitemap $sitemap): void
    {
        $this->blueprint = $blueprint;
        $this->prototype = $prototype;
        $this->sitemap = $sitemap;
    }
}
