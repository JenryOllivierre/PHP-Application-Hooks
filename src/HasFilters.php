<?php

namespace JenryOllivierre\Hooks;

/**
 * @author      Jenry Ollivierre
 * 
 * @since 2.0   First Introduced
 *              Trait methods previously existed in a class.
 */

trait HasFilters
{
    /**
     * Add a callback to a specific filter.
     *
     * @since 2.0
     * @param string $name
     * @param callable $callback
     * @param int $priority
     * @param int $params
     * @return void
     */
    public function addFilter(string $name, callable $callback, int $priority = 100, int $params = 0)
    {
        $this->storeHook('filters', $name, $callback, $priority, $params);
    }

    /**
     * Apply all the callbacks that was added to a specific filter.
     *
     * @since 2.0
     * @param string $name
     * @param mixed $value
     * @param array $args
     * @return mixed
     */
    public function applyFilters(string $name, $value, array $args = [])
    {
        return $this->resolveValues('filters', $name, $value, $args);
    }

    /**
     * Check if anything have been added to the filters hook type.
     *
     * @since 2.0
     * @return bool
     */
    public function filtersExist()
    {
        return $this->hookTypeExists('filters');
    }

    /**
     * Check if there have been anything added for a specific filter.
     *
     * @since 2.0
     * @param string $name
     * @return bool
     */
    public function filterExists(string $name)
    {
        return $this->hookExistsByType('filters', $name);
    }

    /**
     * Get all filters that have been added.
     *
     * @since 2.0
     * @return array
     */
    public function getAllFilters()
    {
        return $this->getAllHooksByType('filters');
    }

    /**
     * Get everything that have been added for a specific filter.
     *
     * @since 2.0
     * @param string $name
     * @return array
     */
    public function getFilter(string $name)
    {
        return $this->getHookByType('filters', $name);
    }

    /**
     * Remove all the filters.
     *
     * @since 2.0
     * @return void
     */
    public function removeAllFilters()
    {
        $this->removeAllHooksFromType('filters');
    }

    /**
     * Remove a specific filter.
     *
     * @since 2.0
     * @param string $name
     * @return void
     */
    public function removeFilter(string $name)
    {
        $this->removeHookByType('filters', $name);
    }
}
