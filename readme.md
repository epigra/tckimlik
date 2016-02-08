# TC Kimlik Numarası Kontrolü ve Doğrulaması (Validation of Turkish Identification Number) 


## Kullanım

#### Doğrulama (Verification)

```php
use Epigra\TcKimlik;

$check = TcKimlik::verify('tckimlikno'); //string
var_dump($check);

$data['tcno'] = 'tckimlikno'; 
$check2 = TcKimlik::verify($data); //array
var_dump($check2);
```

#### SOAP Onay (Validation)

```php
use Epigra\TcKimlik;

$data = array(
		'tcno'          => 'tckimlikno',
		'isim'          => 'XXXXX XXX',
		'soyisim'       => 'XXXXXX',
		'dogumyili'     => 'XXXX',
);

$check = TcKimlik::validate($data); //auto uppercase
var_dump($check);

$check2 = TcKimlik::validate($data,false); // auto uppercase false
var_dump($check2);
```

