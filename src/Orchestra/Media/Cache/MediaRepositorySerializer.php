<?php

namespace Orchestra\Media\Cache;

use Orchestra\Media\MediaRepository;

final class MediaRepositorySerializer
{
    public function serialize(MediaRepository $repository): string
    {
        $data = [];

        foreach ($repository->all() as $media) {
            $data[$media->relativePath] = array_keys($media->getTransformations());
        }

        return serialize($data);
    }

    public function unserialize(string $repository): array
    {
        return unserialize($repository);
    }
}
