<?php

namespace Orchestra\Content\Cache;

use Orchestra\Cache\SignatureGeneratorInterface;
use Orchestra\Content\Source;

final class SourceSignatureGenerator
{
    public function __construct(
        private readonly SignatureGeneratorInterface $signature
    ) {
    }

    public function generateFromSource(Source $source): string
    {
        return $this->signature->generateFromFile($source->path);
    }
}
