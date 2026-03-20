<?php

namespace Orchestra\View\Twig;

use Orchestra\Compiler\BuildContextProvider;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

final class ConfigExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly BuildContextProvider $context
    ) {
    }

    public function getGlobals(): array
    {
        $globals = [];

        $globals['website'] = $this->context->get()->prototype()->configs()->get('website');

        return $globals;
    }
}
