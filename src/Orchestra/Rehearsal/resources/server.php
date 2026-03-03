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
use Orchestra\Rehearsal\RehearsalOutputAdapter;
use Orchestra\Rehearsal\Router;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

$app = new RehearsalKernel();
$container = $app->boot();

/** @var \Orchestra\Compiler\PipelineCollection */
$pipelines = $container->get('compiler.pipeline');

$pipelines->get('preview', new RehearsalOutputAdapter())
    ->run(new BuildOptions(
        skipMedia: false,
        cleanupOnly: false,
        ignoreDrafts: false,
        baseUrl: 'http://localhost:8080'
    ));
