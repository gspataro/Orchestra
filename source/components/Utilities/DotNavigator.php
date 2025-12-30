<?php

namespace GSpataro\Utilities;

use GSpataro\Utilities\Exception\DotNavigatorReadOnlyException;

abstract class DotNavigator
{
    /**
     * Store the data
     *
     * @var array
     */

    protected array $data = [];

    /**
     * Make the navigator readonly
     *
     * @var bool
     */

    protected bool $readOnly = false;

    /**
     * Fill the navigator data array
     *
     * @param array $data
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

    /**
     * Get a variable
     *
     * @param string $query
     * @return mixed
     */

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

    /**
     * Verify if a variable exists in the data array
     *
     * @param string $query
     * @return bool
     */

    public function has(string $query): bool
    {
        return !is_null($this->get($query));
    }

    /**
     * Set a variable
     *
     * @param string $query
     * @param mixed $value
     * @return void
     */

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

    /**
     * Delete a variable and return true on success
     *
     * @param string $query
     * @return bool
     */

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
}
