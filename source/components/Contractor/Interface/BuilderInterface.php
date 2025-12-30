<?php

namespace GSpataro\Contractor\Interface;

interface BuilderInterface
{
    /**
     * Execute build
     *
     * @param array $page
     * @return void
     */

    public function compile(array $page): void;
}
