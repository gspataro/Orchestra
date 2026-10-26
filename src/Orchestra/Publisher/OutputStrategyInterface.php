<?php

namespace Orchestra\Publisher;

interface OutputStrategyInterface
{
    public function apply(string $path): string;
}
