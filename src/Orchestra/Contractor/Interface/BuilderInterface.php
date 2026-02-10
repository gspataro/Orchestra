<?php

namespace Orchestra\Contractor\Interface;

use Orchestra\Pages\Page\Page;

interface BuilderInterface
{
    public function compile(Page $page): void;
}
