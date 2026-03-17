<?php

namespace Orchestra\Project\Factory;

use Orchestra\Project\CompilerContext;
use Orchestra\Project\Prototype;

final class PrototypeFactory
{
    public function fromContext(CompilerContext $context): Prototype
    {
        return new Prototype(
            $context->sources,
            $context->relationships,
            $context->schemas,
            $context->mediaVariants,
            $context->configs
        );
    }
}
