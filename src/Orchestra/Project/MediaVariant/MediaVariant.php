<?php

namespace Orchestra\Project\MediaVariant;

readonly class MediaVariant
{
    public string $name;
    public string $format;

    public function toTransformation(string $name, string $publicPath): MediaTransformation
    {
        return new MediaTransformation(
            $name,
            $publicPath,
            $this
        );
    }
}
