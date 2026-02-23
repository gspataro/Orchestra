<?php

namespace Orchestra\Project;

interface ConfigInterface
{
    public function get(string $tag): mixed;
}
