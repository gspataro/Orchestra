<?php

namespace Orchestra\Publisher\Builder;

use Twig\Environment as TwigEnvironment;
use Orchestra\Publisher\Interface\BuilderInterface;
use Orchestra\Pipeline\BuildContext;

abstract class BaseBuilder implements BuilderInterface
{
    /**
     * Initialize page builder
     *
     * @param TwigEnvironment $twig
     */

    public function __construct(
        protected readonly BuildContext $context,
        protected readonly TwigEnvironment $twig
    ) {
    }

    /**
     * Get output path
     *
     * @param string $path
     * @return string
     */

    protected function getOutputPath(string $path): string
    {
        $outputPath = $this->context->paths->output($path . '.html');
        $outputDir = pathinfo($outputPath, PATHINFO_DIRNAME);

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        return $outputPath;
    }
}
