<?php

namespace Orchestra\View\Twig;

use Orchestra\Compiler\BuildContext;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

final class ConfigExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly BuildContext $context
    ) {
    }

    public function getGlobals(): array
    {
        $globals = [];

        $globals['website'] = $this->context->prototype->configs()->get('website');

        return $globals;
    }
}
