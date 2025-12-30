<?php

namespace GSpataro\Library\Interface;

use GSpataro\Library\ErrorEnum;

interface ReaderInterface
{
    /**
     * Compile and return the given data
     *
     * @param string $group
     * @param string $source
     * @return mixed
     */

    public function compile(string $group, string $source): mixed;

    /**
     * Get failed status
     *
     * @return bool
     */

    public function failed(): bool;

    /**
     * Get error
     *
     * @return ErrorEnum
     */

    public function getError(): ErrorEnum;

    /**
     * Get failed source
     *
     * @return string
     */

    public function getFailedSource(): string;
}
