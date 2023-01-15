<?php 

namespace Epigra;

class TcKimlik
{
    private static $validationfields = ['tcno', 'isim', 'soyisim', 'dogumyili'];
    private static $yabancivalidationfields = ["tcno","isim","soyisim","dogumgunu","dogumayi","dogumyili"];

    public static function verify($input)
    {
        $tcno = $input;
        if (is_array($input) && !empty($input['tcno'])) {
            $tcno = $input['tcno'];
        }

        if (is_array($tcno)) {
            $inputKeys = array_keys($tcno);
            $tcno = $input[$inputKeys[0]];
        }

        if (!preg_match('/^[1-9]{1}[0-9]{9}[0,2,4,6,8]{1}$/', $tcno)) {
            return false;
        }
        
        $odd = $tcno[0] + $tcno[2] + $tcno[4] + $tcno[6] + $tcno[8];
        $even = $tcno[1] + $tcno[3] + $tcno[5] + $tcno[7];
        $digit10 = ($odd * 7 - $even) % 10;
        $total = ($odd + $even + $tcno[9]) % 10;

        if ($digit10 != $tcno[9] ||  $total != $tcno[10]) {
            return false;
        }

        return true;
    }

    public static function validate(array $data, $auto_uppercase = true)
    {
        if (! self::verify($data)) {
            return false;
        }

        if (count(array_diff(self::$validationfields, array_keys($data))) != 0) {
            return false;
        }
        $yabanci=isset($data['yabanci']) && $data['yabanci'];
        if($yabanci)
            $response = self::yabanciKimlikValidate( $data,$auto_uppercase);
        else
            $response = self::tcKimlikValidate( $data,$auto_uppercase);

        return (strip_tags($response) === 'true') ? true : false;
    }
    private static function tcKimlikValidate(Array $data,$auto_uppercase = TRUE)
    {
        if($auto_uppercase){
            foreach(self::$validationfields as $field){
                $data[$field] = self::tr_uppercase($data[$field]);
            }
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
            CURLOPT_URL                => 'https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx',
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS        => $post_data,
            CURLOPT_RETURNTRANSFER    => true,
            CURLOPT_SSL_VERIFYPEER    => false,
            CURLOPT_HEADER            => false,
            CURLOPT_HTTPHEADER        => array(
                    'POST /Service/KPSPublic.asmx HTTP/1.1',
                    'Host: tckimlik.nvi.gov.tr',
                    'Content-Type: text/xml; charset=utf-8',
                    'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/TCKimlikNoDogrula"',
                    'Content-Length: '.strlen($post_data)
            ),
        );
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function tr_uppercase($string){
        $string = str_replace(array('i'), array('İ'), $string);
        return mb_convert_case($string, MB_CASE_UPPER, "UTF-8");
    }


    private static function yabanciKimlikValidate(Array $data,$auto_uppercase = TRUE)
    {
        if($auto_uppercase){
            foreach(self::$yabancivalidationfields as $field){
                $data[$field] = mb_convert_case($data[$field], MB_CASE_UPPER, "UTF-8");
            }
        }

        $post_data = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
			<soap:Body>
				<YabanciKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
					<KimlikNo>'.$data['tcno'].'</KimlikNo>
					<Ad>'.$data['isim'].'</Ad>
					<Soyad>'.$data['soyisim'].'</Soyad>
					<DogumGun>'.$data['dogumgunu'].'</DogumGun>
					<DogumAy>'.$data['dogumayi'].'</DogumAy>
					<DogumYil>'.$data['dogumyili'].'</DogumYil>
				</YabanciKimlikNoDogrula>
			</soap:Body>
		</soap:Envelope>';

        $ch = curl_init();

        // CURL options
        $options = array(
            CURLOPT_URL				=> 'https://tckimlik.nvi.gov.tr/Service/KPSPublicYabanciDogrula.asmx',
            CURLOPT_POST			=> true,
            CURLOPT_POSTFIELDS		=> $post_data,
            CURLOPT_RETURNTRANSFER	=> true,
            CURLOPT_SSL_VERIFYPEER	=> false,
            CURLOPT_HEADER			=> false,
            CURLOPT_HTTPHEADER		=> array(
                'POST /Service/KPSPublicYabanciDogrula.asmx HTTP/1.1',
                'Host: tckimlik.nvi.gov.tr',
                'Content-Type: text/xml; charset=utf-8',
                'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/YabanciKimlikNoDogrula"',
                'Content-Length: '.strlen($post_data)
            ),
        );
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;

    }
}
