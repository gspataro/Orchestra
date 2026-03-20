<?php

namespace Orchestra\View\Elements\Audio;

use Orchestra\View\ViewElement;

final class AudioElement extends ViewElement
{
    protected string $name = 'audio';

    protected function data(array $data = []): array
    {
        $src = $data['src'] ?? '';

        if (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) {
            $data['sources'] = [
                [
                    'src' => $src,
                    'type' => $data['type'] ?? ''
                ]
            ];

            return $data;
        }

        $audio = $this->media->request($src);

        if (is_null($audio)) {
            return [];
        }

        $data['sources'] = [
            [
                'src' => $audio->publicPath,
                'type' => $audio->mimeType
            ]
        ];

        return $data;
    }
}
