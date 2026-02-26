<?php

namespace Orchestra\Publisher;

use Orchestra\Pages\Page;

interface BuilderInterface
{
    public function compile(Page $page): mixed;
}
