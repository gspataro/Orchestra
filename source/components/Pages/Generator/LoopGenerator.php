<?php

namespace GSpataro\Pages\Generator;

final class LoopGenerator extends BaseGenerator
{
    /**
     * Generate pages based on schema
     *
     * @param array $schema
     * @return void
     */

    public function generate(array $schema): void
    {
        $contents = $schema['contents'];
        $basedOn = $this->archive->get($schema['generate_based_on']);

        if (empty($basedOn)) {
            return;
        }

        unset($contents[$schema['generate_based_on']]);

        $this->createCollection(
            $schema['tag'],
            $schema['template'],
            $schema['builder'],
            $contents
        );

        foreach ($basedOn as $contentTag => $content) {
            $tag = $schema['tag'] . '.' . $contentTag;
            $content['id'] = $contentTag;

            $this->addPageToCollection(
                $schema['tag'],
                $contentTag,
                $this->sitemap->add($tag, $schema['slug'] . '/' . $contentTag),
                [$schema['tag'] => $content]
            );
        }
    }
}
