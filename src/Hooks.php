<?php

namespace JenryOllivierre\Hooks;

class Hooks extends HooksFoundation implements Filterable, Actionable
{
	use HasFilters;
	use HasActions;
}
