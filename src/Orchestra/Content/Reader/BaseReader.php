<?php

namespace Orchestra\Content\Reader;

use Orchestra\Content\Archive;
use Orchestra\Content\ReaderInterface;
use Orchestra\Pipeline\BuildContext;

abstract class BaseReader implements ReaderInterface
{
    /**
     * Initialize reader
     *
     * @param Archive $archive
     */

    public function __construct(
        protected readonly BuildContext $context,
        protected readonly Archive $archive
    ) {
    }

    /**
     * Get complete path to source
     *
     * @param string $source
     * @return string
     */

    protected function getPath(string $source): string
    {
        if (str_starts_with($source, $this->context->paths->data())) {
            return $source;
        }

        if (str_starts_with('/', $source)) {
            $source = substr($source, 1);
        }

        return $this->context->paths->data($source);
    }

    /**
     * Convert file name to tag
     *
     * @param string $filename
     * @return string
     */

    protected function fileToTag(string $filename): string
    {
        $tag = str_replace(' ', '_', strtolower($filename));
        return $tag;
    }

    /**
     * Content compiler
     *
     * @param string $source
     * @return mixed
     */

    abstract protected function compiler(string $source): mixed;

    /**
     * Handle a single file
     *
     * @param string $group
     * @param string $source
     * @param bool $collection
     * @return mixed
     */

    protected function handleOne(string $group, string $source, bool $collection = false): mixed
    {
        if ($this->archive->has($source)) {
            return $this->archive->get($source);
        }

        if (str_contains($source, ' ')) {
            return [];
        }

        $filename = pathinfo($source, PATHINFO_FILENAME);
        $tag = $collection ? $group . '.' . $this->fileToTag($filename) : $group;
        $result = $this->compiler($this->getPath($source));

        $this->archive->set($tag, $result);

        return $result;
    }

    /**
     * Handle multiple files
     *
     * @param string $group
     * @param array $sources
     * @return array
     */

    protected function handleMultiple(string $group, array $sources): array
    {
        $results = [];

        foreach ($sources as $source) {
            $filename = pathinfo($source, PATHINFO_FILENAME);
            $tag = $this->fileToTag($filename);

            $results[$tag] = $this->handleOne($group, $source, true);
        }

        return $results;
    }

    /**
     * Handle pattern
     *
     * @param string $group
     * @param string $pattern
     * @return array
     */

    protected function handlePattern(string $group, string $pattern): array
    {
        $results = [];

        foreach (glob($this->getPath($pattern)) as $file) {
            if (!is_file($file)) {
                continue;
            }

            $filename = pathinfo($file, PATHINFO_FILENAME);
            $tag = $this->fileToTag($filename);

            $results[$tag] = $this->handleOne($group, $file, true);
        }

        return $results;
    }

    /**
     * Compile and return the given data
     *
     * @param string $group
     * @param string $source
     * @return array
     */

    public function compile(string $group, string $source): array
    {
        if (is_file($this->getPath($source))) {
            $result = $this->handleOne($group, $source);
            return $result;
        }

        if (str_contains($source, ';')) {
            $sources = explode(';', $source);
            return $this->handleMultiple($group, $sources);
        }

        return $this->handlePattern($group, $source);
    }
}
