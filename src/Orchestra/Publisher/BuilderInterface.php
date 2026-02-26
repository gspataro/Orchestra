<?php

namespace Orchestra\Publisher;

use Orchestra\Page\Page;

interface BuilderInterface
{
    public function compile(Page $page): mixed;
}
