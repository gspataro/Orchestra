<?php

namespace Orchestra\Publisher\Adapter;

use Orchestra\Compiler\BuildContextProvider;
use Orchestra\Publisher\AdapterInterface;

final class FilesystemAdapter implements AdapterInterface
{
    public function __construct(
        private readonly BuildContextProvider $context
    ) {
    }

    public function handle(string $path, mixed $content): void
    {
        $outputPath = $this->context->get()->paths()->output($path);
        $outputDir = pathinfo($outputPath, PATHINFO_DIRNAME);

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        file_put_contents($outputPath, $content);
    }
}
