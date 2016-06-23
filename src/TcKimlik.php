<?php
namespace Epigra\TcKimlik;

use Illuminate\Support\Str;

class TcKimlik {
	/**
	 * const fields to be validated
	 * @var array
	 */
	private static $validationfields = ["tcno","isim","soyisim","dogumyili" ];
	/**
	 * Verifies the Turkish Identity Number
	 * @param  string
	 * @return bool
	 */
	public static function verify($input)
	{
		$tcno = $input;

		if(is_array($input) && !empty($input['tcno'])) {
			$tcno = $input['tcno'];
		}

		if(!preg_match('/^[1-9]{1}[0-9]{9}[0,2,4,6,8]{1}$/', $tcno)){
			return false;
		}

		$odd = $tcno[0] + $tcno[2] + $tcno[4] + $tcno[6] + $tcno[8];
		$even = $tcno[1] + $tcno[3] + $tcno[5] + $tcno[7];
		$digit10 = ($odd * 7 - $even) % 10;
		$total = ($odd + $even + $tcno[9]) % 10;

		if ($digit10 != $tcno[9] ||  $total != $tcno[10]){
			return false;
		}

		return true;
	}
	/**
	 * Validates the Turkish Identity Number over HTTP connection to goverment sys
	 * @param  array ['tcno' => string, 'isim' => string, 'soyisim' => string, 'dogumyili' => int]
	 * @return bool
	 */
	public static function validate($data = [])
	{

		if(! static::verify($data)) return false;

		if (count(array_diff(static::$validationfields, array_keys($data))) != 0) {
			return false;
		}

		foreach(static::$validationfields as $field){
			$data[$field] = Str::upper($data[$field]);
		}

		$post_data = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
			<soap:Body>
				<TCKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
					<TCKimlikNo>'.$data['tcno'].'</TCKimlikNo>
					<Ad>'.$data['isim'].'</Ad>
					<Soyad>'.$data['soyisim'].'</Soyad>
					<DogumYili>'.$data['dogumyili'].'</DogumYili>
				</TCKimlikNoDogrula>
			</soap:Body>
		</soap:Envelope>';

		$ch = curl_init();

		// CURL options
		$options = array(
			CURLOPT_URL				=> 'https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx',
			CURLOPT_POST			=> true,
			CURLOPT_POSTFIELDS		=> $post_data,
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_SSL_VERIFYPEER	=> false,
			CURLOPT_HEADER			=> false,
			CURLOPT_HTTPHEADER		=> array(
					'POST /Service/KPSPublic.asmx HTTP/1.1',
					'Host: tckimlik.nvi.gov.tr',
					'Content-Type: text/xml; charset=utf-8',
					'Content-Length: '.strlen($post_data),
					'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/TCKimlikNoDogrula"'
			),
		);
		curl_setopt_array($ch, $options);

		$response = curl_exec($ch);
		curl_close($ch);

		return (strip_tags($response) === 'true') ? true : false;
	}

}