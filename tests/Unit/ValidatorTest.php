<?php

namespace Epigra\Tests;

use Epigra\Tests\TestCase;
use Epigra\TcKimlik;
use Illuminate\Support\Str;

class Validator extends TestCase {
	/**
	 *
	 *@group Unit
	 **/
	public function test_verify_missing_tc_identification_number() {
		$number = 0000;
		$check = TcKimlik::verify($number);

		$validator = \Validator::make(['tcno' => $number], [
			'tcno' 	 => 'required|tckimlik'
		]);

		$this->assertTrue($validator->fails());
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_verify_bad_tc_identification_number() {
		$number = 00000000000;
		$check = TcKimlik::verify($number);

		$validator = \Validator::make(['tcno' => $number], [
			'tcno' 	 => 'required|tckimlik'
		]);

		$this->assertTrue($validator->fails());
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_verify_bad_tc_identification_number_regex_match() {
		$number = 10000000001;
		$check = TcKimlik::verify($number);

		$validator = \Validator::make(['tcno' => $number], [
			'tcno' 	 => 'required|tckimlik'
		]);

		$this->assertTrue($validator->fails());
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_verify_good_tc_identification_regex_match() {
		$number = 10774881040;
		$check = TcKimlik::verify($number);

		$validator = \Validator::make(['tcno' => $number], [
			'tcno' 	 => 'required|tckimlik'
		]);

		$this->assertFalse($validator->fails());
	}

	/**
	 *
	 *@group Unit
	 **/
	public function test_verify_env_tc_identification_number() {
		$number = $this->TCIdentificationNumber;
		$check = TcKimlik::verify($number);

		$validator = \Validator::make(['tcno' => $number], [
			'tcno' 	 => 'required|tckimlik'
		]);

		$this->assertFalse($validator->fails());
	}
}