<?php

use GSpataro\CLI\Output;
use Orchestra\Application\Kernel\ApplicationKernel;
use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Compiler\Paths;
use Orchestra\Compiler\Pipeline\BuildPipeline;
use Orchestra\Console\ConsoleOutputAdapter;

$tempDir = sys_get_temp_dir() . '/orchestra-build-' . uniqid();

beforeAll(function () use ($tempDir) {
    mkdir($tempDir);

    $app =  new ApplicationKernel()->boot();

    $paths = new Paths(
        dirname(__DIR__) . '/Fixtures/project',
        $tempDir
    );

    $context = new BuildContext($paths);
    $output = new ConsoleOutputAdapter(new Output());

    $pipeline = new BuildPipeline(
        $app,
        $context,
        $output
    );
    $pipeline->run(new BuildOptions());
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
    $this->assertFileExists($tempDir . '/categoria/hello-world/index.html');
});

afterAll(function () use ($tempDir) {
    rmdir($tempDir);
});
