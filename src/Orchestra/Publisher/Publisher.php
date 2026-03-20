<?php

namespace Orchestra\Publisher;

use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\BuildContextProvider;

final class Publisher
{
    public function __construct(
        private readonly BuildContextProvider $context
    ) {
    }

    public function publish(string $path, mixed $content): void
    {
        $outputPath = $this->context->get()->paths()->output($path . '.html');
        $outputDir = pathinfo($outputPath, PATHINFO_DIRNAME);

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        file_put_contents($outputPath, $content);
    }
}
