<?php

namespace JenryOllivierre\Hooks;

/**
 * @author      Jenry Ollivierre
 * 
 * @since 1.0   First Introduced.
 * 
 * @since 2.0   Introduced final keyword. 
 *              Extending the HooksFoundation class.
 *              Implemented the Filterable & Actionable interfaces.
 *              Used the HasFilters & HasActions traits.
 */

final class Hooks extends HooksFoundation implements Filterable, Actionable
{
	use HasFilters;
	use HasActions;
}
