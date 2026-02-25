<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\ReaderInterface;
use Orchestra\Content\ContentPayload;
use Orchestra\Project\Source\ResolvedSource;

abstract class BaseReader implements ReaderInterface
{
    /**
     * @param ResolvedSource $source
     * @return ContentPayload|ContentPayload[]
     */
    abstract protected function compiler(ResolvedSource $source): ContentPayload|array;

    protected function generateContentId(ResolvedSource $source): mixed
    {
        return sha1($source->group . ':' . $source->relativePath);
    }

    protected function generateContentTag(ResolvedSource $source): mixed
    {
        $fileName = pathinfo($source->relativePath, PATHINFO_FILENAME);
        return $source->group . '.' . $fileName;
    }

    protected function contentFromSource(ResolvedSource $source, mixed $body, array $metadata = []): ContentPayload
    {
        return new ContentPayload(
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
     * @return ContentPayload|ContentPayload[]
     */
    public function compile(ResolvedSource|array $source): ContentPayload|array
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
