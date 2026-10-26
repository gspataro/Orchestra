<?php

namespace Orchestra\Publisher\Strategy;

use Orchestra\Publisher\OutputStrategyInterface;

final class PrettyOutputStrategy implements OutputStrategyInterface
{
    public function apply(string $path): string
    {
        if (str_ends_with($path, 'index')) {
            return $path . '.html';
        }

        return pathJoin($path, 'index.html');
    }
}
