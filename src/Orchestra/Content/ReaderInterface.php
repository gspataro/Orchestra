<?php

namespace Orchestra\Content;

use Orchestra\Content\Source;

interface ReaderInterface
{
    /**
     * @param Source $source
     * @return ContentPayload
     */
    public function compile(Source $source): ContentPayload;
}
