<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Content\ContentRepository;
use Orchestra\Compiler\BuildOptions;

final class RelationshipsRuntime extends Runtime
{
    private ContentRepository $contents;

    public function run(BuildOptions $options): bool
    {
        $this->contents = $this->container->get('content.repository');

        $this->output->info('Processing contents relationships');

        foreach ($this->context->prototype()->sources() as $source) {
            $contents = $this->contents->group($source->group);
            $relationships = $this->context->prototype()->relationships()->group($source->group);

            if (empty($relationships)) {
                continue;
            }

            foreach ($contents as $content) {
                $related = [];

                foreach ($relationships as $relationship) {
                    $this->output->info("Creating relation between '{$source->group}' and '{$relationship->with}'");

                    $related[$relationship->with] = $this->contents->group($relationship->with)
                        ->query()
                        ->where(
                            $relationship->field,
                            $relationship->operator,
                            $content->get($relationship->value)
                        )
                        ->get();
                }

                $this->contents->replace($content->withRelationships($related));
            }
        }

        return true;
    }
}
