<?php

namespace Orchestra\Publisher;

use Orchestra\Pages\Page\Page;

interface BuilderInterface
{
    public function compile(Page $page): mixed;
}
