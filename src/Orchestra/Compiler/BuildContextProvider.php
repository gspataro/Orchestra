<?php

namespace Orchestra\Compiler;

final class BuildContextProvider
{
    private BuildContext $context;

    public function set(BuildContext $context): void
    {
        if (isset($this->context)) {
            return;
        }

        $this->context = $context;
    }

    public function get(): BuildContext
    {
        return $this->context;
    }
}
