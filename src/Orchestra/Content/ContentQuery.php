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

    public function where(string $field, mixed $value): self
    {
        $this->filters[] = fn(Content $c) => $c->get($field) == $value;
        return $this;
    }

    public function whereIn(string $field, array $values): self
    {
        $this->filters[] = fn(Content $c) => in_array($c->get($field), $values, true);
        return $this;
    }

    public function skip(int $offset): static
    {
        $this->skip = $offset;
        return $this;
    }

    public function limit(int $offset): static
    {
        $this->limit = $offset;
        return $this;
    }

    public function orderBy(string $field, string|int $order = 'asc'): static
    {
        $this->orderField = $field;
        $this->orderDirection = match ($order) {
            'asc' => SORT_ASC,
            'desc' => SORT_DESC,
            default => $order
        };
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
                $orderField = $this->orderField;
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
