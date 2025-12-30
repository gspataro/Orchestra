<?php

namespace GSpataro\Localization;

final class Locales
{
    /**
     * Store languages
     *
     * @var array
     */

    private array $languages = [];

    /**
     * Add a language
     *
     * @param Language $language
     * @return void
     */

    public function addLanguage(Language $language): void
    {
        if (isset($this->languages[$language->key])) {
            throw new Exception\LanguageFoundException(
                "Language already registered: {$language->key}"
            );
        }

        $this->languages[$language->key] = $language;
    }

    /**
     * Get a language
     *
     * @param string $key
     * @return Language
     */

    public function getLanguage(string $key): Language
    {
        if (!isset($this->languages[$key])) {
            throw new Exception\LanguageNotFoundException(
                "Language not found: {$key}"
            );
        }

        return $this->languages[$key];
    }

    /**
     * Get all the languages
     *
     * @return array
     */

    public function getAll(): array
    {
        return $this->languages;
    }
}
