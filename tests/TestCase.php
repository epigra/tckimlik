<?php

namespace Epigra\Tests;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
class TestCase extends OrchestraTestCase {

	protected $TCIdentificationNumber;
	protected $FirstName;
	protected $LastName;
	protected $BirthYear;
	protected $BirthMonth;
	protected $BirthDay;
	protected $Foreigner;

	protected function getPackageProviders($app)
	{
	    return [
	    	\Epigra\TCKimlikServiceProvider::class,
	    ];
	}

	protected function getPackageAliases($app)
	{
	    return [
	        'TcKimlik' => \Epigra\TcKimlik::class,
	    ];
	}

	protected function setUp(): void {
		parent::setUp();

		$this->TCIdentificationNumber = env('TC_IDENTIFICATION_NUMBER');
		$this->FirstName = env('FIRST_NAME');
		$this->LastName = env('LAST_NAME');
		$this->BirthYear = env('BIRTH_YEAR');
		$this->BirthMonth = env('BIRTH_MONTH');
		$this->BirthDay = env('BIRTH_DAY');
		$this->Foreigner = env('FOREIGNER');
	}

	protected function generateResponseArray($TCIdentificationNumber, $FirstName, $LastName, $BirthYear, $BirthMonth=null, $BirthDay=null, $Foreigner=false) {
		return [
			'tcno' => $TCIdentificationNumber,
			'isim' => $FirstName,
			'soyisim' => $LastName,
			'dogumyili' => $BirthYear,
			'dogumayi' => $BirthMonth,
			'dogumgunu' => $BirthDay,
			'yabanci' => $Foreigner,
		];
	}

	protected function generateFakeTCIdentificationNumer() {
		$pass = "";
		while(strlen($pass) !== 11) {
			$pass .= rand(1, 9);
		}
		return substr($pass, 0, 11);
	}
}