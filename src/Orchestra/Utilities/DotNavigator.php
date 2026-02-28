<?php

namespace Orchestra\Utilities;

use Orchestra\Utilities\Exception\DotNavigatorReadOnlyException;

abstract class DotNavigator
{
    /** @var array<string|int,mixed> */
    protected array $data = [];

    protected bool $readOnly = false;

    /**
     * @param array<string|int,mixed> $data
     * @return void
     */
    protected function fill(array $data): void
    {
        if ($this->readOnly && !empty($this->data)) {
            throw new DotNavigatorReadOnlyException(
                "You can't refill the DotNavigator data when in readonly mode."
            );
        }

        $this->data = $data;
    }

    public function get(string $query): mixed
    {
        $keys = explode('.', $query);
        $current = $this->data;

        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                $current = null;
                break;
            }

            $current = $current[$key];
        }

        return $current;
    }

    public function has(string $query): bool
    {
        return !is_null($this->get($query));
    }

    public function set(string $query, mixed $value): void
    {
        if ($this->readOnly) {
            throw new DotNavigatorReadOnlyException(
                "You can't set a variable when the navigator is in readonly mode."
            );
        }

        $keys = explode('.', $query);
        $current = &$this->data;

        foreach ($keys as $key) {
            $current = &$current[$key];
        }

        $current = $value;
    }

    public function delete(string $query): bool
    {
        if ($this->readOnly) {
            throw new DotNavigatorReadOnlyException(
                "You can't delete a variable when the navigator is in readonly mode."
            );
        }

        $keys = explode('.', $query);
        $current = &$this->data;

        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                return false;
            }

            if ($key === end($keys)) {
                unset($current[$key]);
                return true;
            }

            $current = &$current[$key];
        }

        return false;
    }

    /**
     * @return array<string,mixed>
     */
    public function all(): array
    {
        return $this->data;
    }
}
