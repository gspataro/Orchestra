<?php

use GSpataro\CLI\Output;
use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Compiler\Paths;
use Orchestra\Compiler\Pipeline\BuildPipeline;
use Orchestra\Console\ConsoleOutputAdapter;
use Orchestra\Test\Fixtures\TestKernel;
use Orchestra\Test\Fixtures\TestOutputAdapter;

$tempDir = sys_get_temp_dir() . '/orchestra-' . uniqid();
$outputDir = $tempDir . '/public';
$cacheDir = $tempDir . '/cache';

beforeAll(function () use ($tempDir, $outputDir, $cacheDir) {
    mkdir($tempDir);

    $app =  new TestKernel()->boot();

    $paths = Paths::builder(dirname(__DIR__) . '/Fixtures/project')
        ->output($outputDir)
        ->cache($cacheDir)
        ->build();

    $context = new BuildContext($paths);
    $output = new TestOutputAdapter();

    $pipeline = new BuildPipeline(
        $app,
        $context,
        $output
    );
    $pipeline->run(new BuildOptions(
        skipMedia: true
    ));
});

it('builds a single page', function () use ($outputDir) {
    $this->assertFileExists($outputDir . '/index.html');
});

it('skips drafts', function () use ($outputDir) {
    $this->assertFileDoesNotExist($outputDir . '/future.html');
});

it('builds loop pages', function () use ($outputDir) {
    $this->assertFileExists($outputDir . '/articolo/hello-world/index.html');
});

it('skips drafted content in loops', function () use ($outputDir) {
    $this->assertFileDoesNotExist($outputDir . '/articolo/future.html');
});

it('builds archives', function () use ($outputDir) {
    $this->assertFileExists($outputDir . '/blog/index.html');
});

it('builds collections', function () use ($outputDir) {
    $this->assertFileExists($outputDir . '/categoria/lorem-ipsum/index.html');
});

afterAll(function () use ($tempDir) {
    recursiveDelete($tempDir);
});
