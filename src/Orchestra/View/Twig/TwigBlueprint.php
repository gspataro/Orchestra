<?php

namespace Orchestra\View\Twig;

use Orchestra\Pipeline\BuildContext;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;

final class TwigBlueprint extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly BuildContext $context
    ) {
    }

    public function getGlobals(): array
    {
        $globals = [];

        $globals['website'] = $this->context->blueprint->get('website');

        return $globals;
    }
}
