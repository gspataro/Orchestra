<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\Archive;
use Orchestra\Content\Content;
use Orchestra\Content\ReaderInterface;
use Orchestra\Pipeline\BuildContext;
use Orchestra\Project\Source\ResolvedSource;

abstract class BaseReader implements ReaderInterface
{
    /**
     * @param ResolvedSource $source
     * @return Content|Content[]
     */
    abstract protected function compiler(ResolvedSource $source): Content|array;

    protected function generateContentId(ResolvedSource $source): mixed
    {
        return sha1($source->group . ':' . $source->relativePath);
    }

    protected function generateContentTag(ResolvedSource $source): mixed
    {
        $fileName = pathinfo($source->relativePath, PATHINFO_FILENAME);
        return $source->group . '.' . $fileName;
    }

    protected function contentFromSource(ResolvedSource $source, mixed $body, array $metadata = []): Content
    {
        return new Content(
            $this->generateContentId($source),
            $this->generateContentTag($source),
            $source->group,
            $source->path,
            $body,
            $metadata
        );
    }

    /**
     * @param ResolvedSource|ResolvedSource[] $source
     * @return Content[]|Content
     */
    public function compile(ResolvedSource|array $source): array|Content
    {
        if (!is_array($source)) {
            return $this->compiler($source);
        }

        $contents = [];

        foreach ($source as $singleSource) {
            $results = $this->compiler($singleSource);

            if (!is_array($results)) {
                $contents[] = $results;
                continue;
            }

            foreach ($results as $result) {
                $contents[] = $result;
            }
        }

        return $contents;
    }
}
