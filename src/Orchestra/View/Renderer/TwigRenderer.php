<?php

namespace Orchestra\View\Renderer;

use Orchestra\View\RendererInterface;
use Twig\Environment;

final class TwigRenderer implements RendererInterface
{
    public function __construct(
        private readonly Environment $twig
    ) {
    }

    public function templateExists(string $template): bool
    {
        return $this->twig->getLoader()->exists($template . '.twig');
    }

    public function render(string $template, array $data = []): string
    {
        return $this->twig->render($template . '.twig', $data);
    }
}
