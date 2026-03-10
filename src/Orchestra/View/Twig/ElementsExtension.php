<?php

namespace Orchestra\View\Twig;

use Orchestra\View\ElementCollection;
use Orchestra\View\ElementsRenderer;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class ElementsExtension extends AbstractExtension
{
    public function __construct(
        private readonly ElementCollection $elements,
        private readonly ElementsRenderer $renderer
    ) {
    }

    /**
     * @param string $name
     * @param array<string|int,mixed> $data
     * @return string
     */
    public function element(string $name, array $data = []): string
    {
        $element = $this->elements->get($name);

        if (!$element) {
            return '';
        }

        return $element->render($data);
    }

    public function render(string $html): string
    {
        return $this->renderer->render($html);
    }

    public function getFunctions()
    {
        $functions = [];

        $functions[] = new TwigFunction('element', [$this, 'element'], [
            'is_safe' => ['html']
        ]);

        return $functions;
    }

    public function getFilters()
    {
        $filters = [];

        $filters[] = new TwigFilter('orchestra_content', [$this, 'render'], [
            'is_safe' => ['html']
        ]);

        return $filters;
    }
}
