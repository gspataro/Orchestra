<?php

namespace GSpataro\Finder;

final class Research
{
    /**
     * Store items selection
     *
     * @var array
     */

    private array $selection;

    /**
     * Store where clause
     *
     * @var array
     */

    private array $where;

    /**
     * Store skip offset
     *
     * @var int
     */

    private int $skip;

    /**
     * Store limit offset
     *
     * @var int
     */

    private int $limit;

    /**
     * Store order by criteria
     *
     * @var string
     */

    private array $orderBy;

    /**
     * Store fetched data
     *
     * @var array
     */

    private array $result;

    /**
     * Initialize research
     *
     * @param array $content
     */

    public function __construct(
        private readonly array $content
    ) {
    }

    /**
     * Select specific items in the content
     *
     * @param array $items
     * @return static
     */

    public function select(array $items): static
    {
        if (!isset($this->selection)) {
            $this->selection = $items;
        }

        return $this;
    }

    /**
     * Select items based on a condition
     *
     * @param string $field
     * @param mixed $value
     * @return static
     */

    public function where(string $field, mixed $value): static
    {
        if (!isset($this->where)) {
            $this->where = [
                'field' => $field,
                'value' => $value
            ];
        }

        return $this;
    }

    /**
     * Skip items
     *
     * @param int $offset
     * @return static
     */

    public function skip(int $offset): static
    {
        if (!isset($this->skip)) {
            $this->skip = $offset;
        }

        return $this;
    }

    /**
     * Limit the number of items to select
     *
     * @param int $offset
     */

    public function limit(int $offset): static
    {
        if (!isset($this->limit)) {
            $this->limit = $offset;
        }

        return $this;
    }

    /**
     * Set order by criteria
     *
     * @param string $column
     * @param string $order
     * @return static
     */

    public function orderBy(string $column, string $order = 'asc'): static
    {
        if (!isset($this->orderBy)) {
            $this->orderBy = [
                'column' => $column,
                'order' => $order === 'asc' ? SORT_ASC : SORT_DESC
            ];
        }

        return $this;
    }

    /**
     * Get results
     *
     * @return array
     */

    public function fetch(): array
    {
        if (isset($this->result)) {
            return $this->result;
        }

        $this->result = $this->content;

        $this->performSelection();
        $this->performWhere();
        $this->performSort();
        $this->performLimit();

        return $this->result;
    }

    /**
     * Get a nested value
     *
     * @param string $field
     * @param array $data
     * @return mixed
     */

    private function getNestedValue(string $field, array $data): mixed
    {
        foreach (explode('.', $field) as $index) {
            if (!isset($data[$index])) {
                return null;
            }

            $data = $data[$index];
        }

        return $data;
    }

    /**
     * Filter results based on selection
     *
     * @return void
     */

    private function performSelection(): void
    {
        if (empty($this->selection)) {
            return;
        }

        $this->result = array_filter($this->result, function ($item) {
            return in_array($item, $this->selection);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Filter results based on where clause
     *
     * @return void
     */

    private function performWhere(): void
    {
        if (empty($this->where)) {
            return;
        }

        foreach ($this->result as $tag => $item) {
            $value = $this->getNestedValue($this->where['field'], $item);

            if (is_array($value) && !in_array($this->where['value'], $value)) {
                unset($this->result[$tag]);
                continue;
            }

            if (!is_array($value) && $value !== $this->where['value']) {
                unset($this->result[$tag]);
            }
        }
    }

    /**
     * Shrink results based on skip/limit
     *
     * @return void
     */

    private function performLimit(): void
    {
        if (!isset($this->skip) && !isset($this->limit)) {
            return;
        }

        $this->result = array_slice(
            $this->result,
            $this->skip ?? 0,
            $this->limit ?? null,
            true
        );
    }

    /**
     * Sort results
     *
     * @return void
     */

    private function performSort(): void
    {
        if (empty($this->orderBy)) {
            return;
        }

        $column = [];

        foreach ($this->result as $id => $item) {
            $column[$id] = $this->getNestedValue($this->orderBy['column'], $item);
        }

        array_multisort($column, $this->orderBy['order'], $this->result);
    }
}
