# TC Kimlik Numarası Kontrolü ve Doğrulaması
#(Verification & Validation of Turkish Identification Number)

## Yükleme
composer üzerinden:
```
composer require epigra/tckimlik
````
demeniz yeterli olacaktır.

## Kullanım

#### Doğrulama (Verification)

```php
use Epigra\TcKimlik\TcKimlik;

$check = TcKimlik::verify('tckimlikno'); //string
var_dump($check); //bool

$data['tcno'] = 'tckimlikno';
$check2 = TcKimlik::verify($data); //array
var_dump($check2); //bool
```

#### SOAP Onay (Validation)

```php
use Epigra\TcKimlik\TcKimlik;

$data = array(
		'tcno'          => 'tckimlikno',
		'isim'          => 'XXXXX XXX',
		'soyisim'       => 'XXXXXX',
		'dogumyili'     => 'XXXX',
);

$check = TcKimlik::validate($data); //auto uppercase forced
var_dump($check);

```

#### Laravel Service Provider

`config/app.php` dosyası içerisindeki providers arrayi altına

```
Epigra\TcKimlik\Laravel\TcKimlikServiceProvider::class
```

satırını ekledikten sonra

```php
php artisan vendor:publish
```

demenizle birlikte dil dosyası `resources/lang/vendor/tckimlik/en|tr/tckimlik-validation.php`dizinine yayınlanacaktır. Buradan dilediğiniz hata mesajını yazabilirsiniz.

Dilerseniz Facade kullanabilir

```php
use TcKimlik;
```
veya PSR-4 Namespacing kuralları dahilinde

```php
use Epigra\TcKimlik\TcKimlik
```
şeklinde sınıfın kendisine de erişebilirsiniz.

Routes veya Controller içerisinde

```php

$rules = ['form-alan-adi' => 'required|tckimlik'];

$validator  = Validator::make(Request::all(), $rules);

if($validator->fails()) {
	var_dump($validator->errors());
} else {
	var_dump(Request::get('alan-adi'));
}
```
şeklinde kullanarak TC Kimliğini kontrol etmiş hem de HTTP üzerinden sağlamasını yapmış olursunuz.