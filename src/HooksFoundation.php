<?php

namespace JenryOllivierre\Hooks;

abstract class HooksFoundation implements Hookable
{
    /**
     * To store our hooks.
     *
     * @since 1.0
     * @var array
     */
    private $hooks = [];

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

        foreach ($this->sortByPriority($filters) as $priority => $priorityFilters) {
            foreach ($priorityFilters as $filter) {
                $value = $this->resolveValue($value, $filter['callback'], $args, $filter['params']);
            }
        }

        return $value;
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

        foreach ($this->sortByPriority($tasks) as $priority => $priorityTasks) {
            foreach ($priorityTasks as $task) {
                $response .= $this->resolveTask($task['callback'], $args, $task['params']);
            }
        }

        return $return ? $response : null;
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
    protected function hookExistsByType(string $hookType, string $name)
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
    protected function getAllHooksByType(string $type)
    {
        return $this->hookTypeExists($type)
            ? $this->hooks[$type]
            : [];
    }

    /**
     * Remove all hooks from a hook type.
     *
     * @since 1.0
     * @param string $type
     * @return void
     */
    protected function removeAllHooksFromType(string $type)
    {
        $this->hooks[$type] = [];
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
    protected function getHookByType(string $type, string $name)
    {
        return $this->hookExists($type, $name)
            ? $this->hooks[$type][$name]
            : [];
    }

    /**
     * Remove a hook type.
     *
     * @since 1.0
     * @param string $type
     * @return void
     */
    protected function removeHookType(string $type)
    {
        unset($this->hooks[$type]);
    }

    /**
     * Remove a specific hook for a particular hook type.
     *
     * @since 1.0
     * @param string $hookType
     * @param string $name
     * @return void
     */
    protected function removeHookByType(string $hookType, string $name)
    {
        unset($this->hooks[$hookType][$name]);
    }

    /**
     * Resolve a single value callback.
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
     * Resolve a single task callback.
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
     * Get the arguments that are to be passed to the callback
     * based on the $params value given.
     *
     * @since 1.0
     * @param array $args
     * @param int $params
     * @return array
     */
    private function getArgsPassable(array $args = [], int $params = 0)
    {
        return empty($params) ? $args : array_slice($args, 0, $params)
    }
}
