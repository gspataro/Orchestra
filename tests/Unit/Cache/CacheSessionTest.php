<?php

use Orchestra\Cache\CacheSession;

describe('CacheSession', function () {
    it('starts empty', function () {
        expect((new CacheSession())->all())->toBeEmpty();
    });

    it('tracks a single added key', function () {
        $session = new CacheSession();
        $session->add('/cache/abc/ab123.cache');
        expect($session->all())->toContain('/cache/abc/ab123.cache');
    });

    it('preserves insertion order across multiple keys', function () {
        $session = new CacheSession();
        $session->add('first');
        $session->add('second');
        $session->add('third');
        expect($session->all())->toBe(['first', 'second', 'third']);
    });
});
