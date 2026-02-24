<?php

namespace Orchestra\Compiler;

class Paths
{
    public readonly string $output;
    public readonly string $data;
    public readonly string $media;
    public readonly string $views;
    public readonly string $assets;

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
    }

    public function root(string $path = ''): string
    {
        return pathJoin($this->root, $path);
    }

    public function output(string $path = ''): string
    {
        return pathJoin($this->output, $path);
    }

    public function data(string $path = ''): string
    {
        return pathJoin($this->data, $path);
    }

    public function media(string $path = ''): string
    {
        return pathJoin($this->media, $path);
    }

    public function views(string $path = ''): string
    {
        return pathJoin($this->views, $path);
    }

    public function assets(string $path = ''): string
    {
        return pathJoin($this->assets, $path);
    }
}
