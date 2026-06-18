<?php

namespace Orchestra\Compiler\Runtime;

use Exception;
use Orchestra\Publisher\BuilderCollection;
use Orchestra\Page\PageCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Publisher\OutputRegistry;
use Orchestra\Publisher\OutputStrategyCollection;
use Orchestra\Publisher\Publisher;
use Orchestra\Publisher\Strategy\HtmlOutputStrategy;
use Twig\Error\LoaderError;

final class PagesRuntime extends Runtime
{
    private PageCollection $pages;
    private BuilderCollection $builders;
    private Publisher $publisher;
    private OutputStrategyCollection $outputStrategies;
    private OutputRegistry $outputRegistry;

    public function run(BuildOptions $options): bool
    {
        if ($options->cleanupOnly) {
            $this->output->warning('Skipping pages build');
            return true;
        }

        $this->output->info('Building pages');

        $this->pages = $this->container->get('pages.collection');
        $this->builders = $this->container->get('publisher.builders');
        $this->publisher = $this->container->get('publisher');
        $this->outputStrategies = $this->container->get('publisher.strategies');
        $this->outputRegistry = $this->container->get('publisher.registry');

        /**
         * @todo Make output strategy configurable. For now, we're using the current strategy.
         */
        $strategy = $this->outputStrategies->get('html');

        foreach ($this->pages as $page) {
            try {
                $builder = $this->builders->get($page->schema->builder);
            } catch (Exception $e) {
                $this->output->error(
                    "Builder '{$page->schema->builder}' requested by '{$page->schema->tag}' schema not found."
                );
                return false;
            }

            try {
                $output = $builder->compile($page);
            } catch (LoaderError $e) {
                $this->output->error("Error in '{$page->schema->tag}' schema: " . $e->getMessage());
                return false;
            }

            $path = $strategy->apply($page->permalink);
            $this->outputRegistry->add($path);
            $this->publisher->publish($path, $output);
        }

        return true;
    }
}
