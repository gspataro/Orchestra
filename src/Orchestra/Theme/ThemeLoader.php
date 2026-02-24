<?php

namespace Orchestra\Theme;

use Orchestra\Compiler\BuildContext;

final class ThemeLoader
{
    public function __construct(
        private readonly BuildContext $context
    ) {
    }

    public function load(): ?Theme
    {
        $theme = $this->context->prototype->configs()->get('website.theme') ?? 'pianoforte';
        $themeDirectory = $this->context->paths->themes($theme);

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
            $themeDirectory
        );
    }
}
