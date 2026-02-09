<?php

namespace Orchestra\Pipeline;

use Orchestra\Project\Blueprint;
use Orchestra\Project\Prototype;
use Orchestra\Project\Sitemap;

final class BuildContext
{
    public Blueprint $blueprint;
    public Prototype $prototype;
    public Sitemap $sitemap;

    public function setContext(Blueprint $blueprint, Prototype $prototype, Sitemap $sitemap): void
    {
        $this->blueprint = $blueprint;
        $this->prototype = $prototype;
        $this->sitemap = $sitemap;
    }
}
