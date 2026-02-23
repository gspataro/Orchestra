<?php

namespace Orchestra\Media\Adapter;

use Orchestra\Media\Media;
use Orchestra\Media\AdapterInterface;
use Orchestra\Project\MediaVariant\MediaTransformation;

abstract class BaseAdapter implements AdapterInterface
{
    /** @var string[] */
    protected array $supports = [];

    /**
     * @return string[]
     */
    public function supports(): array
    {
        return $this->supports;
    }

    abstract public function handler(Media $media, ?MediaTransformation $transformation): void;

    public function process(Media $media, ?MediaTransformation $transformation = null): void
    {
        $dirname = pathinfo($media->publicPath, PATHINFO_DIRNAME);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }

        $this->handler($media, $transformation);
    }
}
