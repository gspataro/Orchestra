<?php

namespace Orchestra\Content;

use Orchestra\Content\ErrorEnum;

interface ReaderInterface
{
    /**
     * Compile and return the given data
     *
     * @param string $group
     * @param string $source
     * @return mixed
     */

    public function compile(string $group, string $source): mixed;
}
