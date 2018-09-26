<?php

namespace JenryOllivierre\Hooks;

final class Hooks extends HooksFoundation implements Filterable, Actionable
{
	use HasFilters;
	use HasActions;
}
