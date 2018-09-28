<?php

namespace JenryOllivierre\Hooks;

/**
 * @author      Jenry Ollivierre
 * 
 * @since 1.0   First Introduced as HooksContract
 * 
 * @since 2.0   Renamed to Hookable
 *              Moved majority of previous functions to their own interface.
 */


interface Hookable
{
	/**
	 * Get all the hooks that have been added.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function getAll();
}
