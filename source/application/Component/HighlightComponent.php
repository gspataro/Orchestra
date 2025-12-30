<?php

namespace GSpataro\Application\Component;

use Tempest\Highlight\Highlighter;
use GSpataro\DependencyInjection\Component;

final class HighlightComponent extends Component
{
    public function register(): void
    {
        $this->container->add('tempest.highlight', function ($container, $args): object {
            return new Highlighter();
        }, false);
    }

    public function boot(): void
    {
    }
}
