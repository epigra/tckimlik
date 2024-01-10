<?php

namespace Epigra\Tests;

use Epigra\Tests\TestCase;
use Epigra\TcKimlik;
class VerifyTCIdentificationNumberTest extends TestCase {
	/**
	 *
	 *@group Unit
	 **/
	public function test_verify_missing_tc_identification_number() {
		$number = 0000;
		$check = TcKimlik::verify($number);

		$this->assertFalse($check);

		$check = TcKimlik::verify([
			'tcno' => $number
		]);

		$this->assertFalse($check);
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_verify_bad_tc_identification_number() {
		$number = 00000000000;
		$check = TcKimlik::verify($number);

		$this->assertFalse($check);

		$check = TcKimlik::verify([
			'tcno' => $number
		]);

		$this->assertFalse($check);
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_verify_bad_tc_identification_number_regex_match() {
		$number = 10000000001;
		$check = TcKimlik::verify($number);

		$this->assertFalse($check);

		$check = TcKimlik::verify([
			'tcno' => $number
		]);

		$this->assertFalse($check);
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_verify_good_tc_identification_regex_match() {
		$number = 10774881040;
		$check = TcKimlik::verify($number);
		
		$this->assertTrue($check);

		$check = TcKimlik::verify([
			'tcno' => $number
		]);
		
		$this->assertTrue($check);
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_verify_env_tc_identification_number() {
		$number = $this->TCIdentificationNumber;
		$check = TcKimlik::verify($number);
		
		$this->assertTrue($check);

		$check = TcKimlik::verify([
			'tcno' => $number
		]);
		
		$this->assertTrue($check);
	}
}