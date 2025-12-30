<?php

namespace GSpataro\Pages\Interface;

interface GeneratorInterface
{
    /**
     * Generate pages based on schema
     *
     * @param array $schema
     * @return void
     */

    public function generate(array $schema): void;
}
