<?php

namespace GSpataro\Library\Reader;

final class TextReader extends BaseReader
{
    /**
     * Handle a single file
     *
     * @param string $source
     * @return mixed
     */

    protected function compiler(string $source): mixed
    {
        return file_get_contents($this->getPath($source));
    }
}
