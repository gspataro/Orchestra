<?php

namespace GSpataro\Application\Component;

use League\CommonMark\MarkdownConverter;
use GSpataro\DependencyInjection\Component;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;

final class MarkdownComponent extends Component
{
    public function register(): void
    {
        $this->container->add('markdown.environment', function ($container, $args): object {
            return new Environment($args['options'] ?? []);
        });

        $this->container->add('markdown.converter', function ($container, $args): object {
            return new MarkdownConverter(
                $container->get('markdown.environment')
            );
        });
    }

    public function boot(): void
    {
        $environment = $this->container->get('markdown.environment', [
            'options' => [
                'safe' => false,
                'heading_permalink' => [
                    'insert' => 'after',
                    'html_class' => 'heading-permalink invisible'
                ],
                'table_of_contents' => [
                    'position' => 'placeholder',
                    'placeholder' => '[ARTICLE:TOC]'
                ]
            ]
        ]);

        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new FrontMatterExtension());
        $environment->addExtension(new HeadingPermalinkExtension());
        $environment->addExtension(new TableOfContentsExtension());
    }
}
