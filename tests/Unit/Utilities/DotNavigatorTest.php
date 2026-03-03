<?php

use Orchestra\Test\Stubs\DotNavigatorStub;
use Orchestra\Utilities\Exception\DotNavigatorReadOnlyException;

describe('get()', function () {
    it('returns a top-level scalar', function () {
        expect(new DotNavigatorStub(['name' => 'Orchestra'])->get('name'))->toBe('Orchestra');
    });

    it('navigates nested keys with dot notation', function () {
        expect(new DotNavigatorStub(['website' => ['url' => 'https://example.com']])->get('website.url'))
            ->toBe('https://example.com');
    });

    it('handles deep nesting', function () {
        expect(new DotNavigatorStub(['a' => ['b' => ['c' => 42]]])->get('a.b.c'))->toBe(42);
    });

    it('returns null for a missing top-level key', function () {
        expect(new DotNavigatorStub()->get('missing'))->toBeNull();
    });

    it('returns null for a missing nested key', function () {
        expect(new DotNavigatorStub(['website' => ['url' => 'x']])->get('website.missing'))->toBeNull();
    });

    it('returns null when traversing through a non-array segment', function () {
        expect(new DotNavigatorStub(['name' => 'Orchestra'])->get('name.sub'))->toBeNull();
    });

    it('returns an array value as-is', function () {
        expect(new DotNavigatorStub(['tags' => ['php', 'static']])->get('tags'))->toBe(['php', 'static']);
    });
});

describe('has()', function () {
    it('returns true for an existing key', function () {
        expect(new DotNavigatorStub(['name' => 'Orchestra'])->has('name'))->toBeTrue();
    });

    it('returns true for a nested key', function () {
        expect(new DotNavigatorStub(['a' => ['b' => 'value']])->has('a.b'))->toBeTrue();
    });

    it('returns false for a missing key', function () {
        expect(new DotNavigatorStub()->has('missing'))->toBeFalse();
    });

    it('returns false when value is explicitly null', function () {
        expect(new DotNavigatorStub(['key' => null])->has('key'))->toBeFalse();
    });
});

describe('set()', function () {
    it('sets a top-level value', function () {
        $navigator = new DotNavigatorStub();
        $navigator->set('name', 'Orchestra');

        expect($navigator->get('name'))->toBe('Orchestra');
    });

    it('creates intermediate keys for nested paths', function () {
        $navigator = new DotNavigatorStub();
        $navigator->set('website.url', 'https://example.com');

        expect($navigator->get('website.url'))->toBe('https://example.com');
    });

    it('overwrites an existing value', function () {
        $navigator = new DotNavigatorStub(['name' => 'Old']);
        $navigator->set('name', 'New');

        expect($navigator->get('name'))->toBe('New');
    });

    it('throws when in read-only mode', function () {
        $navigator = new DotNavigatorStub(['name' => 'Orchestra'], readOnly: true);

        expect(fn () => $navigator->set('name', 'New'))->toThrow(DotNavigatorReadOnlyException::class);
    });
});

describe('delete()', function () {
    it('deletes an existing key and returns true', function () {
        $navigator = new DotNavigatorStub(['name' => 'Orchestra']);

        expect($navigator->delete('name'))->toBeTrue();
        expect($navigator->has('name'))->toBeFalse();
    });

    it('returns false when key does not exist', function () {
        expect(new DotNavigatorStub()->delete('missing'))->toBeFalse();
    });

    it('deletes a nested key while leaving siblings intact', function () {
        $navigator = new DotNavigatorStub(['a' => ['b' => 'delete-me', 'c' => 'keep']]);
        $navigator->delete('a.b');

        expect($navigator->has('a.b'))->toBeFalse();
        expect($navigator->has('a.c'))->toBeTrue();
    });

    it('throws when in read-only mode', function () {
        $navigator = new DotNavigatorStub(['name' => 'Orchestra'], readOnly: true);

        expect(fn () => $navigator->delete('name'))->toThrow(DotNavigatorReadOnlyException::class);
    });
});

describe('fill()', function () {
    it('populates data correctly', function () {
        $navigator = new DotNavigatorStub([], false);
        $navigator->expose(['key' => 'value']);

        expect($navigator->get('key'))->toBe('value');
    });

    it('allows first fill in read-only mode when data is empty', function () {
        $navigator = new DotNavigatorStub([], true);
        $navigator->expose(['key' => 'value']);

        expect($navigator->get('key'))->toBe('value');
    });

    it('throws on refill in read-only mode when data already exists', function () {
        $navigator = new DotNavigatorStub(['key' => 'value'], true);
        expect(fn () => $navigator->expose(['key' => 'new']))->toThrow(DotNavigatorReadOnlyException::class);
    });
});

describe('all()', function () {
    it('returns the full data array', function () {
        $data = ['a' => 1, 'b' => ['c' => 2]];
        expect(new DotNavigatorStub($data)->all())->toBe($data);
    });

    it('returns an empty array when no data was set', function () {
        expect(new DotNavigatorStub()->all())->toBe([]);
    });
});
