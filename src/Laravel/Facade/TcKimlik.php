<?php
namespace Epigra\TcKimlik\Laravel\Facade;

use \Illuminate\Support\Facades\Facade;

class TcKimlik extends Facade
{
	/**
	 * {@inheritDoc}
	 */
	protected static function getFacadeAccessor()
	{
	    return 'tckimlik';
	}
}