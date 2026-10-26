<?php

namespace Orchestra\Compiler;

final readonly class Paths
{
    public function __construct(
        public string $root,
        public string $output,
        public string $data,
        public string $media,
        public string $themes,
        public string $cache
    ) {
    }

    public function root(string ...$path): string
    {
        return pathJoin($this->root, ...$path);
    }

    public function output(string ...$path): string
    {
        return pathJoin($this->output, ...$path);
    }

    public function data(string ...$path): string
    {
        return pathJoin($this->data, ...$path);
    }

    public function media(string ...$path): string
    {
        return pathJoin($this->media, ...$path);
    }

    public function themes(string ...$path): string
    {
        return pathJoin($this->themes, ...$path);
    }

    public function cache(string ...$path): string
    {
        return pathJoin($this->cache, ...$path);
    }

    public static function builder(string $root): PathsBuilder
    {
        return new PathsBuilder($root);
    }
}
