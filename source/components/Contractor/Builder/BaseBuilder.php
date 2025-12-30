<?php

namespace GSpataro\Contractor\Builder;

use Twig\Environment as TwigEnvironment;
use GSpataro\Contractor\Interface\BuilderInterface;

abstract class BaseBuilder implements BuilderInterface
{
    /**
     * Initialize page builder
     *
     * @param TwigEnvironment $twig
     */

    public function __construct(
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
        $outputPath = pathJoin(DIR_OUTPUT, $path) . '.html';
        $outputDir = pathinfo($outputPath, PATHINFO_DIRNAME);

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        return $outputPath;
    }
}
