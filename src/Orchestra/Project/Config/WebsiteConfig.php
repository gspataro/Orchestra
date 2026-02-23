<?php

namespace Orchestra\Project\Config;

use Orchestra\Project\ConfigInterface;

final class WebsiteConfig implements ConfigInterface
{
    public function __construct(
        private readonly array $configs
    ) {
    }

    public function get(string $tag): mixed
    {
        return $this->configs[$tag] ?? null;
    }
}
