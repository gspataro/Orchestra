<?php

namespace Orchestra\View\Twig;

use Orchestra\View\ElementCollection;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class ElementsExtension extends AbstractExtension
{
    public function __construct(
        private readonly ElementCollection $elements
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


    public function getFunctions()
    {
        $functions = [];

        $functions[] = new TwigFunction('element', [$this, 'element'], [
            'is_safe' => ['html']
        ]);

        return $functions;
    }
}
