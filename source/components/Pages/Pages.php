<?php

namespace GSpataro\Pages;

use GSpataro\Utilities\DotNavigator;

final class Pages extends DotNavigator
{
    /**
     * Get all pages
     *
     * @return array
     */

    public function getAll(): array
    {
        return $this->data;
    }
}
