<?php

namespace Orchestra\Cache\SignatureGenerator;

use Orchestra\Cache\SignatureGeneratorInterface;

final class ShaSignatureGenerator implements SignatureGeneratorInterface
{
    public function generateFromSeeds(string|int ...$seeds): string
    {
        return hash('sha256', implode(':', $seeds));
    }

    public function generateFromFile(string $path): string
    {
        return hash_file('sha256', $path);
    }
}
