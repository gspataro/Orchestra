<?php

namespace GSpataro\Application\Component;

use Dotenv\Dotenv;
use GSpataro\DependencyInjection\Component;

class DotenvComponent extends Component
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $dotenv = Dotenv::createImmutable(DIR_ROOT);
        $dotenv->load();
    }
}
