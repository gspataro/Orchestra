<?php

use Orchestra\Cache\SignatureGenerator\ShaSignatureGenerator;

beforeEach(function () {
    $this->generator = new ShaSignatureGenerator();
});

describe('generateFromSeeds()', function () {
    it('produces a 64-char lowercase hex string', function () {
        expect($this->generator->generateFromSeeds('seed1', 'seed2'))->toMatch('/^[a-f0-9]{64}$/');
    });

    it('is deterministic for the same seeds', function () {
        expect($this->generator->generateFromSeeds('foo', 'bar', 42))
            ->toBe($this->generator->generateFromSeeds('foo', 'bar', 42));
    });

    it('produces different signatures for different seeds', function () {
        expect($this->generator->generateFromSeeds('seed1'))
            ->not->toBe($this->generator->generateFromSeeds('seed2'));
    });

    it('is sensitive to seed order', function () {
        expect($this->generator->generateFromSeeds('foo', 'bar'))
            ->not->toBe($this->generator->generateFromSeeds('bar', 'foo'));
    });

    it('accepts integer seeds', function () {
        expect($this->generator->generateFromSeeds(1, 2, 3))->toMatch('/^[a-f0-9]{64}$/');
    });

    it('handles a single seed', function () {
        expect($this->generator->generateFromSeeds('only'))->toMatch('/^[a-f0-9]{64}$/');
    });
});

describe('generateFromFile()', function () {
    it('returns a valid sha256 hash', function () {
        $file = tempnam(sys_get_temp_dir(), 'orch_');
        file_put_contents($file, 'hello');
        $signature = $this->generator->generateFromFile($file);
        unlink($file);
        expect($signature)->toMatch('/^[a-f0-9]{64}$/');
    });

    it('is deterministic for the same content', function () {
        $file = tempnam(sys_get_temp_dir(), 'orch_');
        file_put_contents($file, 'deterministic');
        $a = $this->generator->generateFromFile($file);
        $b = $this->generator->generateFromFile($file);
        unlink($file);
        expect($a)->toBe($b);
    });

    it('differs for different file contents', function () {
        $file1 = tempnam(sys_get_temp_dir(), 'orch_a_');
        $file2 = tempnam(sys_get_temp_dir(), 'orch_b_');
        file_put_contents($file1, 'content A');
        file_put_contents($file2, 'content B');
        $a = $this->generator->generateFromFile($file1);
        $b = $this->generator->generateFromFile($file2);
        unlink($file1);
        unlink($file2);
        expect($a)->not->toBe($b);
    });
});
