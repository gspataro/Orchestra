<?php

use GSpataro\CLI\Output;
use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Compiler\Paths;
use Orchestra\Compiler\Pipeline\BuildPipeline;
use Orchestra\Console\ConsoleOutputAdapter;
use Orchestra\Test\Fixtures\TestKernel;
use Orchestra\Test\Fixtures\TestOutputAdapter;

$tempDir = sys_get_temp_dir() . '/orchestra-build-' . uniqid();

beforeAll(function () use ($tempDir) {
    mkdir($tempDir);

    $app =  new TestKernel()->boot();

    $paths = Paths::builder(dirname(__DIR__) . '/Fixtures/project')
        ->output($tempDir)
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

it('builds a single page', function () use ($tempDir) {
    $this->assertFileExists($tempDir . '/index.html');
});

it('builds loop pages', function () use ($tempDir) {
    $this->assertFileExists($tempDir . '/articolo/hello-world.html');
});

it('builds archives', function () use ($tempDir) {
    $this->assertFileExists($tempDir . '/blog/index.html');
});

it('builds collections', function () use ($tempDir) {
    $this->assertFileExists($tempDir . '/categoria/lorem-ipsum/index.html');
});

afterAll(function () use ($tempDir) {
    recursiveDelete($tempDir);
});
