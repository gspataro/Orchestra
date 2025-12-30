<?php

namespace GSpataro\Finder;

use GSpataro\Library\Archive;
use GSpataro\Finder\Exception\InvalidResearchContent;

final class Researcher
{
    /**
     * Store researches
     *
     * @var array
     */

    private $researches;

    /**
     * Initialize Researcher object
     *
     * @param Archive $archive
     */

    public function __construct(
        private readonly Archive $archive
    ) {
    }

    /**
     * Verify if a research exists
     *
     * @param string $label
     * @return bool
     */

    public function has(string $label): bool
    {
        return isset($this->researches[$label]);
    }

    /**
     * Start a new research
     *
     * @param string $label
     * @param string $content
     * @return Research
     */

    public function start(string $label, string $content): Research
    {
        if ($this->has($label)) {
            return $this->researches[$label];
        }

        $data = $this->archive->get($content);

        if (!is_array($data)) {
            throw new InvalidResearchContent(
                "Invalid content provided to research '{$label}'. A valid content must be an array."
            );
        }

        return new Research($data);
    }
}
