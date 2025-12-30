<?php

namespace GSpataro\View;

use GSpataro\Project\Blueprint;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;

final class TwigBlueprint extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly Blueprint $blueprint
    ) {
    }

    public function getGlobals(): array
    {
        $globals = [];

        $globals['website'] = $this->blueprint->get('website');

        return $globals;
    }
}
