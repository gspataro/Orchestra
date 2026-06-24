<?php

namespace Orchestra\Compiler\Runtime;

use Exception;
use Orchestra\Page\PageCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Publisher\OutputStrategyCollection;
use Orchestra\Publisher\Publisher;

final class PagesRuntime extends Runtime
{
    private PageCollection $pages;
    private Publisher $publisher;
    private OutputStrategyCollection $outputStrategies;

    public function run(BuildOptions $options): bool
    {
        if ($options->cleanupOnly) {
            $this->output->warning('Skipping pages build');
            return true;
        }

        $this->output->info('Building pages');

        $this->pages = $this->container->get('pages.collection');
        $this->publisher = $this->container->get('publisher');
        $this->outputStrategies = $this->container->get('publisher.strategies');

        $outputStrategy = $this->outputStrategies->get(
            $this->context->prototype()->configs()->get('orchestra.output_strategy')
        );

        $this->publisher->setOutputStrategy($outputStrategy);

        foreach ($this->pages as $page) {
            try {
                $this->publisher->publish($page);
            } catch (Exception $e) {
                $this->output->error("Error in '{$page->schema->tag}' schema: " . $e->getMessage());
                return false;
            }
        }

        return true;
    }
}
