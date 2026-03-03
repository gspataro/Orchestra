<?php

use PHPUnit\Framework\TestCase;

pest()->extend(TestCase::class)
    ->group('unit')
    ->in('Unit');
