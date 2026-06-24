<?php

namespace Orchestra\View;

interface RendererInterface
{
    public function templateExists(string $template): bool;

    /**
     * @param string $template
     * @param array<array-key, mixed> $data
     * @return string
     */
    public function render(string $template, array $data = []): string;
}
