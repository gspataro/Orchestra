<?php

namespace Orchestra\Compiler;

use Orchestra\Project\Prototype;
use Orchestra\Sitemap\Sitemap;

final class BuildContext
{
    private Prototype $prototype;
    private Sitemap $sitemap;
    private BuildOptions $options;

    public function __construct(
        private Paths $paths
    ) {
    }

    public function setContext(Prototype $prototype, Sitemap $sitemap, BuildOptions $options): void
    {
        $this->prototype = $prototype;
        $this->sitemap = $sitemap;
        $this->options = $options;
    }

    public function paths(): Paths
    {
        return $this->paths;
    }

    public function prototype(): Prototype
    {
        return $this->prototype;
    }

    public function sitemap(): Sitemap
    {
        return $this->sitemap;
    }

    public function options(): BuildOptions
    {
        return $this->options;
    }
}
