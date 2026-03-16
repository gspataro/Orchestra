<?php

namespace Orchestra\View\Elements\Link;

use Orchestra\View\ViewElement;

final class LinkElement extends ViewElement
{
    protected string $name = 'link';

    protected function data(array $data = []): array
    {
        $data['href'] = $this->url->to($data['href']);

        return $data;
    }
}
