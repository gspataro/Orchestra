<?php

namespace GSpataro\Library\Reader;

final class JsonReader extends BaseReader
{
    /**
     * Handle a single file
     *
     * @param string $source
     * @return mixed
     */

    protected function compiler(string $source): mixed
    {
        return json_decode(file_get_contents($source), true);
    }
}
