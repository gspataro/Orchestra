<?php

namespace Orchestra\Theme;

use Orchestra\Theme\Exception\ThemeProviderException;

final class ThemeProvider
{
    private Theme $theme;

    public function set(Theme $theme): void
    {
        if (isset($this->theme)) {
            throw new ThemeProviderException(
                "Theme already set."
            );
        }

        $this->theme = $theme;
    }

    public function get(): Theme
    {
        if (!isset($this->theme)) {
            throw new ThemeProviderException(
                "Theme not set yet."
            );
        }

        return $this->theme;
    }
}
