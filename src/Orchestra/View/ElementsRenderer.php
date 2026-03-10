<?php

namespace Orchestra\View;

final class ElementsRenderer
{
    public function __construct(
        private readonly ElementCollection $elements
    ) {
    }

    public function render(string $text): string
    {
        if (!str_contains($text, '<orchestra-element')) {
            return $text;
        }

        return preg_replace_callback(
            "/<orchestra-element\s+([^>]*?) \/>/s",
            function (array $matches) {
                $attributes = $this->parseAttributes($matches[1]);
                $name = $attributes['name'] ?? null;

                if (!$name) {
                    return $matches[0];
                }

                unset($attributes['name']);

                return $this->elements->get($name)->render($attributes);
            },
            $text
        );
    }

    private function parseAttributes(string $attrString): array
    {
        $attributes = [];
        preg_match_all('/(\w[\w-]*)="([^"]*)"/', $attrString, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $attributes[$match[1]] = htmlspecialchars_decode($match[2]);
        }

        return $attributes;
    }
}
