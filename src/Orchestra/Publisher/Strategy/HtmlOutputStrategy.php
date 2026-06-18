<?php

namespace Orchestra\Publisher\Strategy;

use Orchestra\Publisher\OutputStrategyInterface;

final class HtmlOutputStrategy implements OutputStrategyInterface
{
    public function apply(string $path): string
    {
        return $path . '.html';
    }
}
