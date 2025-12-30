<?php

namespace GSpataro\Localization;

use GSpataro\Localization\Exception\LanguageFileNotFoundException;
use GSpataro\Localization\Exception\LanguageNotFoundException;

final class Language
{
    /**
     * Store dictionaries
     *
     * @var array
     */

    private array $dictionaries = [];

    /**
     * Initialize language
     *
     * @param string $key
     * @param string $path
     */

    public function __construct(
        public readonly string $key,
        public readonly string $path
    ) {
        if (!is_dir($this->path)) {
            throw new LanguageNotFoundException(
                "Language directory not found: {$this->path}"
            );
        }
    }

    /**
     * Load a language file
     *
     * @param string $fileName
     * @return string
     */

    private function loadFile(string $fileName): string
    {
        $filePath = "{$this->path}/{$fileName}.json";

        if (!is_file($filePath)) {
            throw new LanguageFileNotFoundException(
                "Language file not found: {$fileName}"
            );
        }

        return file_get_contents($filePath);
    }

    /**
     * Load a dictionary
     *
     * @param string $fileName
     * @return void
     */

    private function loadDictionary(string $fileName): void
    {
        if (isset($this->dictionaries[$fileName])) {
            return;
        }

        $rawDictionary = $this->loadFile($fileName);
        $this->dictionaries[$fileName] = json_decode($rawDictionary, true);
    }

    /**
     * Get a dictionary
     *
     * @param string $fileName
     * @return array
     */

    public function getDictionary(string $fileName): array
    {
        $this->loadDictionary($fileName);

        return $this->dictionaries[$fileName] ?? [];
    }

    /**
     * Get a language string from a dictionary
     *
     * @param string $query
     * @param string $fileName
     * @return string|null
     */

    public function get(string $query, string $fileName): ?string
    {
        $dictionary = $this->getDictionary($fileName);
        $keys = explode(".", $query);
        $result = $dictionary;

        foreach ($keys as $key) {
            if (!isset($result[$key])) {
                $result = $query;
                break;
            }

            $result = $result[$key];
        }

        return $result;
    }
}
