<?php

use Orchestra\Blueprint\Blueprint;

describe('Blueprint', function () {
    it('loads data via init() and retrieves it with dot notation', function () {
        $blueprint = new Blueprint();

        $blueprint->init([
            'website' => [
                'name' => 'My site',
                'url' => 'https://www.example.com'
            ]
        ]);

        expect($blueprint->get('website.name'))->toBe('My site');
        expect($blueprint->get('website.url'))->toBe('https://www.example.com');
    });

    it('is read-only after init()', function () {
        $blueprint = new Blueprint();

        $blueprint->init([
            'website' => [
                'name' => 'My Site'
            ]
        ]);

        expect(fn () => $blueprint->set('website.name', 'X'))
            ->toThrow(\Orchestra\Utilities\Exception\DotNavigatorReadOnlyException::class);
    });
});
