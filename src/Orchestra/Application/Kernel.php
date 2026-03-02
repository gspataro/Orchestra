<?php

namespace Orchestra\Application;

use GSpataro\DependencyInjection\Container;

abstract class Kernel
{
    abstract public function boot(): Container;
}
