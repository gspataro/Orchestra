<?php

namespace Orchestra\Compiler;

final class PathsBuilder
{
    private string $output;
    private string $data;
    private string $media;
    private string $themes;
    private string $cache;

    public function __construct(
        private readonly string $root
    ) {
        $this->output = $root . '/public';
        $this->data   = $root . '/contents';
        $this->media  = $root . '/contents/media';
        $this->themes = $root . '/resources/themes';
        $this->cache  = $root . '/cache';
    }

    public function build(): Paths
    {
        return new Paths(
            root: $this->root,
            output: $this->output,
            data: $this->data,
            media: $this->media,
            themes: $this->themes,
            cache: $this->cache
        );
    }

    public function output(string $path): static
    {
        $this->output = $path;
        return $this;
    }

    public function data(string $path): static
    {
        $this->data = $path;
        return $this;
    }

    public function media(string $path): static
    {
        $this->media = $path;
        return $this;
    }

    public function themes(string $path): static
    {
        $this->themes = $path;
        return $this;
    }

    public function cache(string $path): static
    {
        $this->cache = $path;
        return $this;
    }
}
