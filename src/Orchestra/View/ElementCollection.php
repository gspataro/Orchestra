<?php

namespace Orchestra\View;

final class ElementCollection
{
    /** @var ViewElement[] */
    private array $elements = [];

    public function add(ViewElement $element): void
    {
        if (isset($this->elements[$element->name()])) {
            return;
        }

        $this->elements[$element->name()] = $element;
    }

    public function get(string $element): ?ViewElement
    {
        return $this->elements[$element] ?? null;
    }
}
