<?php

namespace JenryOllivierre\Hooks;

interface Filterable
{
    /**
     * @since 1.1
     * @author Jenry Ollivierre
     */

    /**
     * Add a callback to a specific filter.
     *
     * @since 1.1
     * @param string $name
     * @param callable $callback
     * @param int $priority
     * @param int $params
     * @return void
     */
    public function addFilter(string $name, callable $callback, int $priority = 100, int $params = 1);

    /**
     * Apply all the callbacks that was added to a specific filter.
     *
     * @since 1.1
     * @param string $name
     * @param mixed $value
     * @param array $args
     * @return mixed
     */
    public function applyFilters(string $name, $value, array $args = []);

    /**
     * Check if anything have been added to the filters hook type.
     *
     * @since 1.1
     * @return bool
     */
    public function filtersExist();

    /**
     * Check if there have been anything added for a specific filter.
     *
     * @since 1.0
     * @param string $name
     * @return bool
     */
    public function filterExists(string $name);

    /**
     * Get all filters that have been added.
     *
     * @since 1.0
     * @return array
     */
    public function getAllFilters();

    /**
     * Get everything that have been added for a specific filter.
     *
     * @since 1.0
     * @param string $name
     * @return array
     */
    public function getFilter(string $name);

    /**
     * Remove all the filters.
     *
     * @since 1.0
     * @return void
     */
    public function removeAllFilters();

    /**
     * Remove a specific filter.
     *
     * @since 1.0
     * @param string $name
     * @return void
     */
    public function removeFilter(string $name);
}
