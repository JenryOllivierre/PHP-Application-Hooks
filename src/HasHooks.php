<?php

namespace JenryOllivierre\Hooks;

trait HasHooks
{
	/**
	 * Get all the hooks that have been added.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function getAll()
	{
		return $this->hooks;
	}

	/**
	 * Check if anything have been added to the filters hook type.
	 *
	 * @since 1.0
	 * @return bool
	 */
	public function filtersExist()
	{
		return $this->hookTypeExists('filters');
	}

	/**
	 * Check if anything have been added to the actions hook type.
	 *
	 * @since 1.0
	 * @return bool
	 */
	public function actionsExist()
	{
		return $this->hookTypeExists('actions');
	}

	/**
	 * Check if there have been anything added for a specific filter.
	 *
	 * @since 1.0
	 * @param string $name
	 * @return bool
	 */
	public function filterExists(string $name)
	{
		return $this->hookExists('filters', $name);
	}

	/**
	 * Check if there has been anything added for a specific action.
	 *
	 * @since 1.0
	 * @param string $name
	 * @return bool
	 */
	public function actionExists(string $name)
	{
		return $this->hookExists('actions', $name);
	}

	/**
	 * Get all filters that have been added.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function getAllFilters()
	{
		return $this->getAllHooks('filters');
	}

	/**
	 * Get all actions that have been added.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function getAllActions()
	{
		return $this->getAllHooks('actions');
	}

	/**
	 * Get everything that have been added for a specific filter.
	 *
	 * @since 1.0
	 * @param string $name
	 * @return array
	 */
	public function getFilter(string $name)
	{
		return $this->getHook('filters', $name);
	}

	/**
	 * Get everything that have been added for a specific action.
	 *
	 * @since 1.0
	 * @param string $name
	 * @return array
	 */
	public function getAction(string $name)
	{
		return $this->getHook('actions', $name);
	}

	/**
	 * Remove all the filters.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function removeAllFilters()
	{
		$this->removeHookType('filters');
	}

	/**
	 * Remove all the actions.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function removeAllActions()
	{
		$this->removeHookType('actions');
	}

	/**
	 * Remove a specific filter.
	 *
	 * @since 1.0
	 * @param string $name
	 * @return void
	 */
	public function removeFilter(string $name)
	{
		$this->removeHook('filters', $name);
	}

	/**
	 * Remove a specific action.
	 *
	 * @since 1.0
	 * @param string $name
	 * @return void
	 */
	public function removeAction(string $name)
	{
		$this->removeHook('actions', $name);
	}

	/**
	 * Check if anything have been added to a particular hook type.
	 *
	 * @since 1.0
	 * @param string $type
	 * @return bool
	 */
	protected function hookTypeExists(string $type)
	{
		return isset($this->hooks[$type]);
	}

	/**
	 * Check if there has been anything set for a specific hook, for a
	 * particular hook type.
	 *
	 * @since 1.0
	 * @param string $hookType
	 * @param string $name
	 * @return bool
	 */
	protected function hookExists(string $hookType, string $name)
	{
		return isset($this->hooks[$hookType][$name]);
	}

	/**
	 * Get everything that has been added to a specific hook type.
	 *
	 * @since 1.0
	 * @param string $type
	 * @return array
	 */
	protected function getAllHooks(string $type)
	{
		if (! $this->hookTypeExists($type)) {
			return [];
		}

		return $this->hooks[$type];
	}

	/**
	 * Get everything that have been added to a specific hook, for a
	 * particular hook type.
	 *
	 * @since 1.0
	 * @param string $type
	 * @param string $name
	 * @return array
	 */
	protected function getHook(string $type, string $name)
	{
		if (! $this->hookExists($type, $name)) {
			return [];
		}

		return $this->hooks[$type][$name];
	}

	/**
	 * Remove all the hooks for a hook type.
	 *
	 * @since 1.0
	 * @param string $type
	 * @return void
	 */
	protected function removeHookType(string $type)
	{
		if (! $this->hookTypeExists($type)) {
			return;
		}

		unset($this->hooks[$type]);
	}

	/**
	 * Remove a specific hook for particular hook type.
	 *
	 * @since 1.0
	 * @param string $hookType
	 * @param string $name
	 * @return void
	 */
	protected function removeHook(string $hookType, string $name)
	{
		if (! $this->hookExists($hookType, $name)) {
			return;
		}

		unset($this->hooks[$hookType][$name]);
	}
}
