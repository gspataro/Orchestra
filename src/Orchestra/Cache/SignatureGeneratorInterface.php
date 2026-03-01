<?php

namespace Orchestra\Cache;

interface SignatureGeneratorInterface
{
    public function generateFromSeeds(string|int ...$seeds): string;
    public function generateFromFile(string $path): string;
}
