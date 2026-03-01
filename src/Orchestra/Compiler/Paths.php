<?php

namespace Orchestra\Compiler;

class Paths
{
    private string $output;
    private string $data;
    private string $media;
    private string $views;
    private string $assets;
    private string $themes;
    private string $cache;

    public function __construct(
        public readonly string $root
    ) {
    }

    public function setDefaults(): void
    {
        $this->output = $this->root . '/public';
        $this->data = $this->root . '/contents/data';
        $this->media = $this->root  . '/contents/media';
        $this->views = $this->root . '/resources/view';
        $this->assets = $this->root . '/resources/assets';
        $this->themes = $this->root . '/resources/themes';
        $this->cache = $this->root . '/cache';
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

    public function views(string ...$path): string
    {
        return pathJoin($this->views, ...$path);
    }

    public function assets(string ...$path): string
    {
        return pathJoin($this->assets, ...$path);
    }

    public function themes(string ...$path): string
    {
        return pathJoin($this->themes, ...$path);
    }

    public function cache(string ...$path): string
    {
        return pathJoin($this->cache, ...$path);
    }
}
