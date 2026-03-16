<?php

namespace Orchestra\Sitemap;

final class Sitemap
{
    /** @var array<string,SitemapResource> */
    private array $byTag = [];

    /** @var array<string,SitemapResource> */
    private array $bySourcePath = [];

    /** @var array<string,SitemapResource> */
    private array $byPermalink = [];

    public function add(SitemapResource $resource): void
    {
        $this->byTag[$resource->tag] = $resource;
        $this->byPermalink[$resource->permalink] = $resource;

        if ($resource->sourcePath) {
            $this->bySourcePath[$resource->sourcePath] = $resource;
        }
    }

    public function fromTag(string $tag): ?SitemapResource
    {
        if (!isset($this->byTag[$tag])) {
            $tag .= '.page-1';
        }

        return $this->byTag[$tag] ?? null;
    }

    public function fromPermalink(string $permalink): ?SitemapResource
    {
        return $this->byPermalink[$permalink] ?? null;
    }

    public function fromSourcePath(string $sourcePath): ?SitemapResource
    {
        return $this->bySourcePath[$sourcePath] ?? null;
    }

    public function get(string $identifier): ?SitemapResource
    {
        return $this->fromTag($identifier)
        ?? $this->fromPermalink($identifier)
        ?? $this->fromSourcePath($identifier)
        ?? null;
    }

    /**
     * @return SitemapResource[]
     */
    public function all(): array
    {
        return array_values($this->byTag);
    }
}
