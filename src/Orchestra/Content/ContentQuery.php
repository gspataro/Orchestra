<?php

namespace Orchestra\Content;

final class ContentQuery
{
    private array $filters = [];

    private int $skip = 0;
    private ?int $limit = null;

    private ?string $orderField = null;
    private int $orderDirection = SORT_ASC;

    /** @var Content[] */
    private array $result;

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
     * @return Content[]
     */
    public function get(): array
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

    private function apply(): array
    {
        if (isset($this->result)) {
            return $this->result;
        }

        $this->result = $this->collection->toArray();

        foreach ($this->filters as $filter) {
            $this->result = array_filter($this->result, $filter);
        }

        if ($this->orderField !== null) {
            usort($this->result, function (Content $a, Content $b) {
                $valueA = $a->get($this->orderField);
                $valueB = $b->get($this->orderField);

                return $this->orderDirection === SORT_ASC
                    ? $valueA <=> $valueB
                    : $valueB <=> $valueA;
            });
        }

        if ($this->skip > 0 || $this->limit !== null) {
            $this->result = array_slice($this->result, $this->skip, $this->limit);
        }

        return $this->result;
    }
}
