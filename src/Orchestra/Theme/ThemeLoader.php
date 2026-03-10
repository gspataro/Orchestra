<?php

namespace Orchestra\Theme;

use Orchestra\Compiler\BuildContextProvider;

final class ThemeLoader
{
    public function __construct(
        private readonly BuildContextProvider $context
    ) {
    }

    public function load(): ?Theme
    {
        $theme = $this->context->get()->prototype()->configs()->get('website.theme') ?? 'pianoforte';
        $themeDirectory = $this->context->get()->paths()->themes($theme);

        if (!is_dir($themeDirectory)) {
            return null;
        }

        $manifest = pathJoin($themeDirectory, 'theme.json');

        if (!is_file($manifest)) {
            return null;
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
