<?php

namespace Orchestra\Application\Kernel;

use Orchestra\Console\ConsoleComponent;
use Orchestra\Publisher\PublisherComponent;
use Orchestra\Content\ContentComponent;
use Orchestra\Page\PagesComponent;
use Orchestra\Project\ProjectComponent;
use Orchestra\Blueprint\BlueprintComponent;
use Orchestra\Cache\CacheComponent;
use Orchestra\Infrastructure\ExceptionHandlerComponent;
use Orchestra\Infrastructure\HighlightComponent;
use Orchestra\Markdown\MarkdownComponent;
use Orchestra\Media\MediaComponent;
use Orchestra\Compiler\CompilerComponent;
use Orchestra\Sitemap\SitemapComponent;
use Orchestra\Theme\ThemeComponent;
use Orchestra\View\ViewComponent;

final class ApplicationKernel extends BaseKernel
{
    protected array $components = [
        ExceptionHandlerComponent::class,
        SitemapComponent::class,
        CompilerComponent::class,
        BlueprintComponent::class,
        ProjectComponent::class,
        HighlightComponent::class,
        CacheComponent::class,
        ThemeComponent::class,
        ViewComponent::class,
        MarkdownComponent::class,
        ContentComponent::class,
        PagesComponent::class,
        PublisherComponent::class,
        MediaComponent::class,
        ConsoleComponent::class
    ];
}
