<?php

namespace Orchestra\Project\MediaVariant;

final readonly class MediaVariant
{
    /**
     * @param string $name
     * @param string|null $format
     * @param array<string,mixed> $options
     */
    public function __construct(
        public string $name,
        public ?string $format = null,
        public array $options = []
    ) {
    }

    public function option(string $option): mixed
    {
        return $this->options[$option] ?? null;
    }

    public function toTransformation(string $name, string $relativePath, string $publicPath): MediaTransformation
    {
        return new MediaTransformation(
            $name,
            $relativePath,
            $publicPath,
            $this
        );
    }
}
