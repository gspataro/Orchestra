<?php

namespace Orchestra\Compiler;

use Orchestra\Compiler\Exception\BuildContextProviderException;

final class BuildContextProvider
{
    private BuildContext $context;

    public function set(BuildContext $context): void
    {
        if (isset($this->context)) {
            throw new BuildContextProviderException(
                "BuildContext already set."
            );
        }

        $this->context = $context;
    }

    public function get(): BuildContext
    {
        if (!isset($this->context)) {
            throw new BuildContextProviderException(
                "BuildContext not set yet."
            );
        }

        return $this->context;
    }
}
