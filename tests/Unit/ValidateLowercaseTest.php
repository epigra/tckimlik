<?php

namespace Epigra\Tests;

use Epigra\Tests\TestCase;
use Epigra\TcKimlik;
use Illuminate\Support\Str;

class ValidateLowercaseTest extends TestCase {
	/**
	 *
	 *@group Unit
	 **/
	public function test_validate_lowercase_missing_tc_identification_number() {
		$check = TcKimlik::validate([
			'isim' => $this->FirstName,
			'soyisim' => $this->LastName,
			'dogumyili' => $this->BirthYear
		], false);

		$this->assertFalse($check);
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_validate_lowercase_missing_first_name() {
		$check = TcKimlik::validate([
			'tcno' => $this->TCIdentificationNumber,
			'soyisim' => $this->LastName,
			'dogumyili' => $this->BirthYear
		], false);

		$this->assertFalse($check);
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_validate_lowercase_missing_last_name() {
		$check = TcKimlik::validate([
			'tcno' => $this->TCIdentificationNumber,
			'isim' => $this->FirstName,
			'dogumyili' => $this->BirthYear
		], false);

		$this->assertFalse($check);
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_validate_lowercase_missing_birth_year() {
		$check = TcKimlik::validate([
			'tcno' => $this->TCIdentificationNumber,
			'isim' => $this->FirstName,
			'soyisim' => $this->LastName
		], false);

		$this->assertFalse($check);
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_validate_lowercase_wrong_info() {
		$check = TcKimlik::validate([
			'tcno' => $this->generateFakeTCIdentificationNumer(),
			'isim' => Str::random(5),
			'soyisim' => Str::random(5),
			'dogumyili' => rand(1945, (int) date('Y', now()->unix()))
		], false);

		$this->assertFalse($check);
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_validate_lowercase_env_definitions() {
		$check = TcKimlik::validate([
			'tcno' => $this->TCIdentificationNumber,
			'isim' => TcKimlik::tr_uppercase($this->FirstName),
			'soyisim' => TcKimlik::tr_uppercase($this->LastName),
			'dogumyili' => $this->BirthYear
		], false);

		$this->assertTrue($check);
	}
}