<?php

namespace Orchestra\Compiler;

use Orchestra\Project\Prototype;
use Orchestra\Project\Sitemap;

final class BuildContext
{
    public readonly Paths $paths;
    public readonly Prototype $prototype;
    public readonly Sitemap $sitemap;
    public readonly BuildOptions $options;

    public function __construct(?Paths $paths = null)
    {
        if ($paths) {
            $this->paths = $paths;
            return;
        }

        $this->paths = new Paths(getcwd());
        $this->paths->setDefaults();
    }

    public function setContext(Prototype $prototype, Sitemap $sitemap, BuildOptions $options): void
    {
        $this->prototype = $prototype;
        $this->sitemap = $sitemap;
        $this->options = $options;
    }
}
