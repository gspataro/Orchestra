<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Cache\CacheSession;
use Orchestra\Compiler\BuildOptions;

final class CacheInvalidationRuntime extends Runtime
{
    private CacheSession $session;

    public function run(BuildOptions $options): bool
    {
        $this->output->info("Invalidating cache");

        $this->session = $this->container->get('cache.session');

        recursiveDelete(
            $this->context->paths()->cache('orchestra'),
            true,
            $this->session->all()
        );

        return true;
    }
}
