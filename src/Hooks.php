<?php

namespace JenryOllivierre\Hooks;

class Hooks implements HooksContract
{
	use HasHooks;

	/**
	 * To store our hooks.
	 *
	 * @since 1.0
	 * @var array
	 */
	private $hooks = [];

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
	public function addFilter(string $name, callable $callback, int $priority = 100, int $params = 0)
	{
		$this->storeHook('filters', $name, $callback, $priority, $params);
	}

	/**
	 * Apply all the callbacks that was added to a specific filter.
	 *
	 * @since 1.0
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
	 * Add a callback to a specific action.
	 *
	 * @since 1.0
	 * @param string $name
	 * @param callable $callback
	 * @param int $priority
	 * @param int $params
	 * @return void
	 */
	public function addAction(string $name, callable $callback, int $priority = 100, int $params = 0)
	{
		$this->storeHook('actions', $name, $callback, $priority, $params);
	}

	/**
	 * Apply all the callbacks that was added to a specific action.
	 *
	 * @since 1.0
	 * @param string $name
	 * @param array $args
	 * @param bool $return
	 * @return mixed|void
	 */
	public function applyActions(string $name, array $args = [], bool $return = false)
	{
		return $this->resolveTasks('actions', $name, $args, $return);
	}

	/**
	 * Store a hook.
	 *
	 * @since 1.0
	 * @param string $type
	 * @param string $name
	 * @param callable $callback
	 * @param int $priority
	 * @param int $params
	 * @return void
	 */
	protected function storeHook(string $type, $name, callable $callback, int $priority = 100, int $params = 1)
	{
		$this->hooks[$type][$name][$priority][] = [
			'callback' => $callback,
			'params' => $params,
		];
	}

	/**
	 * Resolve all the values for a given hook.
	 *
	 * @since 1.0
	 * @param string $hookType
	 * @param string $name
	 * @param mixed $value
	 * @param array $args
	 * @return mixed
	 */
	protected function resolveValues(string $hookType, string $name, $value, array $args = [])
	{
		if (! $filters = $this->getHook($hookType, $name)) {
			return $value;
		}

		$filters = $this->sortByPriority($filters);

		foreach ($filters as $priority => $priorityFilters) {
			foreach ($priorityFilters as $filter) {
				$value = $this->resolveValue($value, $filter['callback'], $args, $filter['params']);
			}
		}

		return $value;
	}

	/**
	 * Resolve a single value for a given hook by using the callback provided.
	 *
	 * @since 1.0
	 * @param mixed $value
	 * @param callable $callback
	 * @param array $args
	 * @param int $params
	 * @return mixed
	 */
	private function resolveValue($value, callable $callback, array $args = [], int $params = 0)
	{
		array_unshift($args, $value);
		return call_user_func_array($callback, $this->getArgsPassable($args, $params));
	}

	/**
	 * Resolve all the tasks for a given hook.
	 *
	 * @since 1.0
	 * @param string $hookType
	 * @param string $name
	 * @param array $args
	 * @param bool $return
	 * @return void
	 */
	protected function resolveTasks(string $hookType, string $name, array $args = [], bool $return = false)
	{
		$response = null;

		if (! $tasks = $this->getHook($hookType, $name)) {
			return;
		}

		$tasks = $this->sortByPriority($tasks);

		foreach ($tasks as $priority => $priorityTasks) {
			foreach ($priorityTasks as $task) {
				$response .= $this->resolveTask($task['callback'], $args, $task['params']);
			}
		}

		if ($return) {
			return $response;
		}
	}

	/**
	 * Resolve a single task.
	 *
	 * @since 1.0
	 * @param callable $callback
	 * @param array $args
	 * @param int $params
	 * @return mixed
	 */
	private function resolveTask(callable $callback, array $args = [], int $params = 0)
	{
		return call_user_func_array($callback, $this->getArgsPassable($args, $params));
	}

	/**
	 * Sort the hooks priority from smallest to largest.
	 *
	 * @since 1.0
	 * @param array $hooks
	 * @return void
	 */
	private function sortByPriority($hooks)
	{
		ksort($hooks);
		return $hooks;
	}

	/**
	 * Get the arguments that are requested to be passed to the callback,
	 * based on the $params value given.
	 *
	 * @since 1.0
	 * @param array $args
	 * @param int $params
	 * @return array
	 */
	private function getArgsPassable(array $args = [], int $params = 0)
	{
		if (empty($params)) {
			return $args;
		}

		return array_slice($args, 0, $params)
	}
}
