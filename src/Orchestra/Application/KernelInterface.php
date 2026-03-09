<?php

namespace Orchestra\Application;

use GSpataro\DependencyInjection\Container;

interface KernelInterface
{
    public function boot(): Container;
}
