<?php

use Orchestra\Content\Cache\SourceSignatureGenerator;
use Orchestra\Content\Source;

it('delegates to the inner signature generator using the source file path', function () {
    $file = tempnam(sys_get_temp_dir(), 'orch_');
    file_put_contents($file, 'content');

    $source = new Source('blog', 'text', $file, 'post.txt');
    $inner = new \Orchestra\Cache\SignatureGenerator\ShaSignatureGenerator();
    $generator = new SourceSignatureGenerator($inner);

    $expected = $inner->generateFromFile($file);
    $actual = $generator->generateFromSource($source);

    unlink($file);

    expect($actual)->toBe($expected);
});
