<?php

namespace Orchestra\View;

use Orchestra\Content\ContentRepository;
use Orchestra\Media\MediaResolver;
use Twig\Environment;

abstract class ViewElement
{
    protected string $name;

    public function __construct(
        protected readonly Environment $twig,
        protected readonly ContentRepository $contents,
        protected readonly MediaResolver $media
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    abstract protected function data(array $data = []): array;

    public function render(array $data = []): string
    {
        $template = $this->twig->resolveTemplate([
            "@orchestra-elements/{$this->name}.twig",
            "@orchestra-elements/" . ucfirst($this->name) . "/{$this->name}.twig"
        ]);
        return $template->render($this->data($data));
    }
}
