<?php

namespace Orchestra\Sitemap;

final class Permalink
{
    /** @var string[] */
    private array $permalinks = [];

    public function generateUnique(string $permalink): string
    {
        if (!in_array($permalink, $this->permalinks)) {
            $this->permalinks[] = $permalink;
            return $permalink;
        }

        while (in_array($permalink, $this->permalinks)) {
            $permalink = addSuffixToFilename($permalink, '-copy');
        }

        $this->permalinks[] = $permalink;
        return $permalink;
    }
}
