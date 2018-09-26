<?php

namespace JenryOllivierre\Hooks;

trait HasActions
{
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
     * Check if there has been anything added for a specific action.
     *
     * @since 1.0
     * @param string $name
     * @return bool
     */
    public function actionExists(string $name)
    {
        return $this->hookExistsByType('actions', $name);
    }

    /**
     * Get all actions that have been added.
     *
     * @since 1.0
     * @return array
     */
    public function getAllActions()
    {
        return $this->getAllHooksByType('actions');
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
        return $this->getHookByType('actions', $name);
    }

    /**
     * Remove all the actions.
     *
     * @since 1.0
     * @return void
     */
    public function removeAllActions()
    {
        $this->removeAllHooksFromType('actions');
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
        $this->removeHookByType('actions', $name);
    }
}
