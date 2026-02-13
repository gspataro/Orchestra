<?php

namespace Orchestra\Content;

final class ContentQuery
{
    private array $filters = [];

    private int $skip = 0;
    private ?int $limit = null;

    private ?string $orderField = null;
    private int $orderDirection = SORT_ASC;

    private ContentCollection $result;

    public function __construct(
        private readonly ContentCollection $collection
    ) {
    }

    public function fromDefinition(ContentQueryDefinition $definition): self
    {
        if (!empty($definition->wheres)) {
            foreach ($definition->wheres as [$field, $operator, $value]) {
                $this->where($field, $operator, $value);
            }
        }

        if ($definition->skip) {
            $this->skip($definition->skip);
        }

        if ($definition->limit) {
            $this->limit($definition->limit);
        }

        if ($definition->orderField) {
            $this->orderBy($definition->orderField, $definition->sortDirection);
        }

        return $this;
    }

    private function normalizeForComparison(mixed $a, mixed $b): mixed
    {
        if (is_int($a) && is_string($b)) {
            $time = strtotime($b);

            if ($time !== false) {
                return $time;
            }
        }

        return $b;
    }

    private function compare(mixed $a, string $operator, mixed $b): bool
    {
        $b = $this->normalizeForComparison($a, $b);

        return match ($operator) {
            '=', '==' => $a == $b,
            '!=', '<>' => $a != $b,
            '>' => $a > $b,
            '>=' => $a >= $b,
            '<' => $a < $b,
            '<=' => $a <= $b,
            'in' => in_array($a, (array) $b, true),
            'contains' => is_string($a) && str_contains($a, (string) $b),
            default => false
        };
    }

    public function where(string $field, string $operator, mixed $value): self
    {
        $this->filters[] = function (Content $c) use ($field, $operator, $value) {
            return $this->compare($c->get($field), $operator, $value);
        };

        return $this;
    }

    public function whereIn(string $field, array $values): self
    {
        $this->where($field, 'in', $values);
        return $this;
    }

    public function whereContains(string $field, string $value): self
    {
        $this->where($field, 'contains', $value);
        return $this;
    }

    public function skip(int $offset): static
    {
        $this->skip = $offset;
        return $this;
    }

    public function limit(?int $offset): static
    {
        $this->limit = $offset;
        return $this;
    }

    public function orderBy(?string $field, int $order): static
    {
        $this->orderField = $field;
        $this->orderDirection = $order;
        return $this;
    }

    /**
     * @return ContentCollection
     */
    public function get(): ContentCollection
    {
        return $this->apply();
    }

    public function first(): ?Content
    {
        return $this->apply()[0] ?? null;
    }

    public function count(): int
    {
        return count($this->apply());
    }

    /**
     * @param integer $perPage
     * @return ContentCollection[]
     */
    public function paginate(int $perPage): array
    {
        $all = $this->get();
        $chunks = array_chunk($all->toArray(), $perPage);

        return array_map(
            fn(array $items) => new ContentCollection($items),
            $chunks
        );
    }

    private function apply(): ContentCollection
    {
        if (isset($this->result)) {
            return $this->result;
        }

        $contents = $this->collection->toArray();

        foreach ($this->filters as $filter) {
            $contents = array_filter($contents, $filter);
        }

        if ($this->orderField !== null) {
            usort($contents, function (Content $a, Content $b) {
                $valueA = $a->get($this->orderField);
                $valueB = $b->get($this->orderField);

                return $this->orderDirection === SORT_ASC
                    ? $valueA <=> $valueB
                    : $valueB <=> $valueA;
            });
        }

        if ($this->skip > 0 || $this->limit !== null) {
            $contents = array_slice($contents, $this->skip, $this->limit);
        }

        $this->result = new ContentCollection($contents);
        return $this->result;
    }
}
