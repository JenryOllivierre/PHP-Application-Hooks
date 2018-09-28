<?php

namespace JenryOllivierre\Hooks;

/**
 * @author      Jenry Ollivierre
 * 
 * @since 2.0   First Introduced
 *              Interface methods previously existed in another interface.
 */

interface Actionable
{
    /**
     * Add a callback to a specific action.
     *
     * @since 2.0
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
     * @since 2.0
     * @param string $name
     * @param array $args
     * @param bool $return
     * @return void
     */
    public function applyActions(string $name, array $args = [], bool $return = false);

    /**
     * Check if anything have been added to the actions hook type.
     *
     * @since 2.0
     * @return bool
     */
    public function actionsExist();

    /**
     * Check if there has been anything added for a specific action.
     *
     * @since 2.0
     * @param string $name
     * @return bool
     */
    public function actionExists(string $name);

    /**
     * Get all actions that have been added.
     *
     * @since 2.0
     * @return array
     */
    public function getAllActions();

    /**
     * Get everything that have been added for a specific action.
     *
     * @since 2.0
     * @param string $name
     * @return array
     */
    public function getAction(string $name);

    /**
     * Remove all the actions.
     *
     * @since 2.0
     * @return void
     */
    public function removeAllActions();

    /**
     * Remove a specific action.
     *
     * @since 2.0
     * @param string $name
     * @return void
     */
    public function removeAction(string $name);
}
