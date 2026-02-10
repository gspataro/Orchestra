<?php

namespace Orchestra\Application;

abstract class Kernel
{
    abstract public function boot(): void;
}
