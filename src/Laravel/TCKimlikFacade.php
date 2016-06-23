<?php
namespace Epigra\TCKimlik\Laravel;

use \Illuminate\Support\Facades\Facade;

class TCKimlikFacade extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 *
	 * @throws \RuntimeException
	 */
	protected static function getFacadeAccessor()
	{
	    return "tckimlik";
	}
}