<?php

namespace Orchestra\Compiler\Pipeline;

use Orchestra\Compiler\Runtime\AssetsRuntime;
use Orchestra\Compiler\Runtime\CacheInvalidationRuntime;
use Orchestra\Compiler\Runtime\CleanupRuntime;
use Orchestra\Compiler\Runtime\SitemapRuntime;
use Orchestra\Compiler\Runtime\ContentsRuntime;
use Orchestra\Compiler\Runtime\CreateContextRuntime;
use Orchestra\Compiler\Runtime\MediaRuntime;
use Orchestra\Compiler\Runtime\PagesRuntime;
use Orchestra\Compiler\Runtime\RelationshipsRuntime;
use Orchestra\Compiler\Runtime\SchemasRuntime;
use Orchestra\Compiler\Runtime\ThemeRuntime;

final class BuildPipeline extends BasePipeline
{
    protected array $runtimes = [
        CreateContextRuntime::class,
        ThemeRuntime::class,
        ContentsRuntime::class,
        RelationshipsRuntime::class,
        SchemasRuntime::class,
        AssetsRuntime::class,
        PagesRuntime::class,
        MediaRuntime::class,
        SitemapRuntime::class,
        CleanupRuntime::class,
        CacheInvalidationRuntime::class
    ];
}
