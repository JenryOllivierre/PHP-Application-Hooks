<?php

namespace JenryOllivierre\Hooks;

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
