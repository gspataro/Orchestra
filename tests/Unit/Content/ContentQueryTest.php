<?php

// Helpers

use Orchestra\Content\Content;
use Orchestra\Content\ContentCollection;
use Orchestra\Content\ContentQuery;
use Orchestra\Project\Definition\Query\QueryDefinition;

function content(array $meta, string $body = ''): Content
{
    return new Content(
        sha1(serialize($meta)),
        'group.item',
        'group',
        '/path/item.md',
        $body,
        $meta
    );
}

function collection(array $items): ContentCollection
{
    return new ContentCollection($items);
}

// Tests

describe('where() operators', function () {
    test('== filters by equality', function () {
        $query = new ContentQuery(collection([content(['status' => 'published']), content(['status' => 'draft'])]));
        expect($query->where('metadata.status', '==', 'published')->count())->toBe(1);
    });

    test('= is an alias for ==', function () {
        $query = new ContentQuery(collection([content(['status' => 'published']), content(['status' => 'draft'])]));
        expect($query->where('metadata.status', '=', 'published')->count())->toBe(1);
    });

    test('!= filters by inequality', function () {
        $query = new ContentQuery(collection([content(['status' => 'published']), content(['status' => 'draft'])]));
        expect($query->where('metadata.status', '!=', 'draft')->count())->toBe(1);
    });

    test('<> is an alias for !=', function () {
        $query = new ContentQuery(collection([content(['status' => 'published']), content(['status' => 'draft'])]));
        expect($query->where('metadata.status', '<>', 'draft')->count())->toBe(1);
    });

    test('> filters greater-than', function () {
        $query = new ContentQuery(collection([content(['views' => 100]), content(['views' => 50])]));
        expect($query->where('metadata.views', '>', 60)->count())->toBe(1);
    });

    test('>= filters greater-than-or-equal', function () {
        $query = new ContentQuery(collection([content(['views' => 100]), content(['views' => 60]), content(['views' => 30])]));
        expect($query->where('metadata.views', '>=', 60)->count())->toBe(2);
    });

    test('< filters less-than', function () {
        $query = new ContentQuery(collection([content(['price' => 10]), content(['price' => 50])]));
        expect($query->where('metadata.price', '<', 20)->count())->toBe(1);
    });

    test('<= filters less-than-or-equal', function () {
        $query = new ContentQuery(collection([content(['price' => 10]), content(['price' => 20]), content(['price' => 50])]));
        expect($query->where('metadata.price', '<=', 20)->count())->toBe(2);
    });

    test('in filters by array membership', function () {
        $query = new ContentQuery(collection([content(['tag' => 'php']), content(['tag' => 'python']), content(['tag' => 'rust'])]));
        expect($query->where('metadata.tag', 'in', ['php', 'rust'])->count())->toBe(2);
    });

    test('contains filters string substring', function () {
        $query = new ContentQuery(collection([content(['title' => 'Hello World']), content(['title' => 'Goodbye'])]));
        expect($query->where('metadata.title', 'contains', 'Hello')->count())->toBe(1);
    });

    test('containsAny filters arrays with overlapping values', function () {
        $query = new ContentQuery(collection([content(['tags' => ['php', 'js']]), content(['tags' => ['python']])]));
        expect($query->where('metadata.tags', 'containsAny', ['php'])->count())->toBe(1);
    });

    test('containsAll filters arrays that contain all specified values', function () {
        $query = new ContentQuery(collection([content(['tags' => ['php', 'js', 'html']]), content(['tags' => ['php']])]));
        expect($query->where('metadata.tags', 'containsAll', ['php', 'js'])->count())->toBe(1);
    });

    test('unknown operator returns no results', function () {
        $query = new ContentQuery(collection([content(['x' => 1])]));
        expect($query->where('metadata.x', 'BOGUS', 1)->count())->toBe(0);
    });

    test('multiple where() clauses are ANDed', function () {
        $query = new ContentQuery(collection([
            content(['status' => 'published', 'views' => 100]),
            content(['status' => 'published', 'views' => 5]),
            content(['status' => 'draft',     'views' => 100]),
        ]));
        expect($query->where('metadata.status', '=', 'published')->where('metadata.views', '>', 50)->count())->toBe(1);
    });

    it('returns empty collection when nothing matches', function () {
        $query = new ContentQuery(collection([content(['status' => 'draft'])]));
        expect($query->where('metadata.status', '=', 'published')->count())->toBe(0);
    });
});

describe('whereIn() and whereContains()', function () {
    test('whereIn() is shorthand for the in operator', function () {
        $query = new ContentQuery(collection([content(['color' => 'red']), content(['color' => 'blue'])]));
        expect($query->whereIn('metadata.color', ['red'])->count())->toBe(1);
    });

    test('whereContains() is shorthand for the contains operator', function () {
        $query = new ContentQuery(collection([content(['body' => 'foo bar']), content(['body' => 'nothing'])]));
        expect($query->whereContains('metadata.body', 'foo')->count())->toBe(1);
    });
});

describe('date comparison normalization', function () {
    it('compares an int timestamp against a date string', function () {
        $past = mktime(0, 0, 0, 1, 1, 2020);
        $query = new ContentQuery(collection([
            content(['date' => $past]),
            content(['date' => mktime(0, 0, 0, 1, 1, 2025)]),
        ]));

        // Items with date > 2023-01-01
        expect($query->where('metadata.date', '>', '2023-01-01')->count())->toBe(1);
    });
});

describe('orderBy()', function () {
    it('sorts ascending', function () {
        $query = new ContentQuery(collection([content(['n' => 3]), content(['n' => 1]), content(['n' => 2])]));
        $result = array_values($query->orderBy('metadata.n', SORT_ASC)->get()->toArray());

        expect($result[0]->get('metadata.n'))->toBe(1);
        expect($result[2]->get('metadata.n'))->toBe(3);
    });

    it('sorts descending', function () {
        $query = new ContentQuery(collection([content(['n' => 1]), content(['n' => 3]), content(['n' => 2])]));
        $result = array_values($query->orderBy('metadata.n', SORT_DESC)->get()->toArray());

        expect($result[0]->get('metadata.n'))->toBe(3);
        expect($result[2]->get('metadata.n'))->toBe(1);
    });
});

describe('skip() and limit()', function () {
    it('skips the first N items', function () {
        $items = array_map(fn ($i) => content(['n' => $i]), range(1, 5));
        expect((new ContentQuery(collection($items)))->skip(3)->count())->toBe(2);
    });

    it('limits to N items', function () {
        $items = array_map(fn ($i) => content(['n' => $i]), range(1, 5));
        expect((new ContentQuery(collection($items)))->limit(2)->count())->toBe(2);
    });

    it('combines skip and limit', function () {
        $items = array_map(fn ($i) => content(['n' => $i]), range(1, 10));
        expect((new ContentQuery(collection($items)))->skip(5)->limit(3)->count())->toBe(3);
    });

    test('limit(null) has no effect', function () {
        $items = array_map(fn ($i) => content(['n' => $i]), range(1, 4));
        expect((new ContentQuery(collection($items)))->limit(null)->count())->toBe(4);
    });
});


describe('paginate()', function () {
    it('splits into the correct number of pages', function () {
        $items = array_map(fn ($i) => content(['n' => $i]), range(1, 10));
        expect((new ContentQuery(collection($items)))->paginate(3))->toHaveCount(4); // 3+3+3+1
    });

    test('each page is a ContentCollection', function () {
        $items = array_map(fn ($i) => content(['n' => $i]), range(1, 4));
        $pages = (new ContentQuery(collection($items)))->paginate(2);
        expect($pages[0])->toBeInstanceOf(ContentCollection::class);
    });
});

describe('first() and count()', function () {
    test('first() returns the first element', function () {
        $a = content(['n' => 1]);
        $b = content(['n' => 2]);

        expect((new ContentQuery(collection([$a, $b])))->first())->toBe($a);
    });

    test('first() returns null when result is empty', function () {
        $a = content(['status' => 'draft']);
        expect((new ContentQuery(collection([$a])))->where('metadata.status', '=', 'published')->first())->toBeNull();
    });

    test('count() returns the total number of results', function () {
        $items = array_map(fn ($i) => content(['n' => $i]), range(1, 7));
        expect((new ContentQuery(collection($items)))->count())->toBe(7);
    });
});

describe('result caching', function () {
    test('calling get() twice returns the same ContentCollection instance', function () {
        $items = array_map(fn ($i) => content(['n' => $i]), range(1, 3));
        $query = new ContentQuery(collection($items));
        expect($query->get())->toBe($query->get());
    });
});

describe('fromDefinition()', function () {
    it('applies wheres, skip, limit and orderBy from QueryDefinition', function () {
        $items = array_map(fn ($i) => content(['n' => $i, 'status' => 'published']), range(1, 10));
        $def = new QueryDefinition(
            'group',
            [['metadata.n', '>', 3]],
            skip: 1,
            limit: 4,
            orderField: 'metadata.n',
            sortDirection: SORT_ASC
        );
        $result = (new ContentQuery(collection($items)))->fromDefinition($def)->get();

        expect(count($result))->toBe(4);
        // first item should be n=5 (items >3 starting at index 0: 4,5,6,7,8,9,10; skip 1 → 5)
        expect(array_values($result->toArray())[0]->get('metadata.n'))->toBe(5);
    });
});
