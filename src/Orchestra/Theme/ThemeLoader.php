<?php

namespace Orchestra\Theme;

use Orchestra\Compiler\BuildContextProvider;
use Orchestra\Theme\Exception\ThemeManifestNotFoundException;
use Orchestra\Theme\Exception\ThemeNotFoundException;

final class ThemeLoader
{
    public function __construct(
        private readonly BuildContextProvider $context
    ) {
    }

    public function load(): Theme
    {
        $theme = $this->context->get()->prototype()->configs()->get('website.theme') ?? 'pianoforte';
        $themeDirectory = $this->context->get()->paths()->themes($theme);

        if (!is_dir($themeDirectory)) {
            throw new ThemeNotFoundException(
                "Theme '{$theme}' not found."
            );
        }

        $manifest = pathJoin($themeDirectory, 'theme.json');

        if (!is_file($manifest)) {
            throw new ThemeManifestNotFoundException(
                "'{$theme}' theme manifest not found."
            );
        }

        $data = json_decode(
            file_get_contents($manifest),
            true
        );

        return new Theme(
            $data['name'] ?? '',
            $themeDirectory,
            new ThemeAssets(
                $data['assets']['driver'] ?? 'static',
                $data['assets']['dir'] ?? 'assets',
                $data['assets']['entries'] ?? []
            )
        );
    }
}
