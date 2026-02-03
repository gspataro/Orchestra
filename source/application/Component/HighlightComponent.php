<?php

namespace GSpataro\Application\Component;

use GSpataro\DependencyInjection\Container;
use GSpataro\Solista\Component;
use Tempest\Highlight\Highlighter;

final class HighlightComponent extends Component
{
    public function register(Container $container): void
    {
        $container->add('tempest.highlight', function ($container, $args): object {
            return new Highlighter();
        }, false);
    }

    public function boot(Container $container): void
    {
    }
}
