<?php

namespace Orchestra\View\Twig;

use Orchestra\Compiler\BuildContext;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;

final class BlueprintExtension extends AbstractExtension implements GlobalsInterface
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
