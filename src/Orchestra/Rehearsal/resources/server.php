#!/usr/bin/env php
<?php

/**
 * Solista - Static website builder
 *
 * @author Giuseppe Spataro <https://github.com/gspataro>
 * @version 1.0.0
 */

use Orchestra\Application\Kernel\RehearsalKernel;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Compiler\CompilerMode;
use Orchestra\Compiler\Pipeline\RehearsalPipeline;
use Orchestra\Rehearsal\RehearsalOutputAdapter;

require_once getcwd() . '/vendor/autoload.php';

$app = new RehearsalKernel();
$container = $app->boot();

/** @var \Orchestra\Compiler\Factory\BuildContextFactory */
$contextFactory = $container->get('compiler.context.factory');

/** @var \Orchestra\Compiler\Factory\PipelineFactory */
$pipelineFactory = $container->get('compiler.pipeline.factory');

$context = $contextFactory->make();
$pipeline = $pipelineFactory->make(
    RehearsalPipeline::class,
    $context,
    new RehearsalOutputAdapter()
);

$pipeline->run(new BuildOptions(
    skipMedia: false,
    cleanupOnly: false,
    ignoreDrafts: false,
    themeDebug: true,
    baseUrl: 'http://localhost:8080',
    mode: CompilerMode::REHEARSAL
));
