<?php

namespace JenryOllivierre\Hooks;

interface HooksContract
{
	/**
	 * Add a callback to a specific filter.
	 *
	 * @since 1.0
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
	 * @since 1.0
	 * @param string $name
	 * @param mixed $value
	 * @param array $args
	 * @return mixed
	 */
	public function applyFilters(string $name, $value, array $args = []);

	/**
	 * Add a callback to a specific action.
	 *
	 * @since 1.0
	 * @param string $name
	 * @param callable $callback
	 * @param int $priority
	 * @param int $params
	 * @return void
	 */
	public function addAction(string $name, callable $callback, int $priority = 100, int $params = 1);

	/**
	 * Apply all the callbacks that was added to a specific action.
	 *
	 * @since 1.0
	 * @param string $name
	 * @param array $args
	 * @param bool $return
	 * @return void
	 */
	public function applyActions(string $name, array $args = [], bool $return = false);

	/**
	 * Get all the hooks that have been added.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function getAll();

	/**
	 * Check if anything have been added to the filters hook type.
	 *
	 * @since 1.0
	 * @return bool
	 */
	public function filtersExist();

	/**
	 * Check if anything have been added to the actions hook type.
	 *
	 * @since 1.0
	 * @return bool
	 */
	public function actionsExist();

	/**
	 * Check if there have been anything added for a specific filter.
	 *
	 * @since 1.0
	 * @param string $name
	 * @return bool
	 */
	public function filterExists(string $name);

	/**
	 * Check if there has been anything added for a specific action.
	 *
	 * @since 1.0
	 * @param string $name
	 * @return bool
	 */
	public function actionExists(string $name);

	/**
	 * Get all filters that have been added.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function getAllFilters();

	/**
	 * Get all actions that have been added.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function getAllActions();

	/**
	 * Get everything that have been added for a specific filter.
	 *
	 * @since 1.0
	 * @param string $name
	 * @return array
	 */
	public function getFilter(string $name);

	/**
	 * Get everything that have been added for a specific action.
	 *
	 * @since 1.0
	 * @param string $name
	 * @return array
	 */
	public function getAction(string $name);

	/**
	 * Remove all the filters.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function removeAllFilters();

	/**
	 * Remove all the actions.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function removeAllActions();

	/**
	 * Remove a specific filter.
	 *
	 * @since 1.0
	 * @param string $name
	 * @return void
	 */
	public function removeFilter(string $name);

	/**
	 * Remove a specific action.
	 *
	 * @since 1.0
	 * @param string $name
	 * @return void
	 */
	public function removeAction(string $name);
}
