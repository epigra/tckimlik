# TC Kimlik Numarası Kontrolü ve Doğrulaması (Validation of Turkish Identification Number) 

Bilgilendirme (Alıntı):

Genel Müdürlüğümüz https://tckimlik.nvi.gov.tr/ public web sayfası doğrulama servisleri 01.08.2025 tarihinde kapatılacağı belirtilmiş olup, 5490 sayılı Nüfus Hizmetleri Kanunun 45. maddesindeki; “(2) Veri paylaşımından yararlanacakları belirlemeye, paylaşımın kapsamına ve hangi yöntemle yapılacağına karar vermek üzere Genel Müdürlük bünyesinde Veri Paylaşımı Kurulu oluşturulur.” hükmü kapsamında oluşturulmuş olan Veri Paylaşımı Kurulunun 2025/4 sayılı kararı ile KPS’de Tüm Kütük Doğrulama Servisine “Seri No” alanı eklenmesine karar verilmiştir. Alınan Kurul kararı kapsamında Tüm Kütük Doğrulama Servisinin 01/08/2025 versiyonu yayınlanmış bulunmaktadır. Entegrasyon süreçlerinin devam etmesinden dolayı web sayfası doğrulama servislerinin kapatılması 30.09.2025 tarihine kadar uzatılmıştır.Not: Genel Müdürlüğümüz https://tckimlik.nvi.gov.tr/ public web sayfası doğrulama servisleri ekran hizmeti olarak kullanıma açık kalmaya devam edecektir.

## Yükleme
composer üzerinden:
```
composer require epigra/tckimlik
````
demeniz yeterli olacaktır.

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

#### Laravel Service Provider

`config/app.php` dosyası içerisindeki providers arrayi altına

```
Epigra\TCKimlikServiceProvider::class
```

satırını ekledikten sonra standart Validation kütüphanesi içerisinde

```php
$validator = Validator::make($data, [
	'tcno' 	 => 'required|tckimlik|unique:tabloadi,sutunadi',
	'isim' => 'required',
	'soyisim' 	 => 'required',
	'dogumyili' => 'required',
]);
```
şeklinde kullanıldıktan sonra verify fonksiyonu otomatik olarak belirtilen alan için çalışarak algoritmik doğrulamayı gerçekleştirecektir.

Verilen hata mesajını değiştirmek isterseniz `resources/lang/dil/validation.php`dosyası içerisine

```php
'tckimlik' => "Vermek istediğiniz hata mesajı"
```

şeklinde tanımlama yapabilirsiniz.


#### Extending Laravel Validator

Öncesinde `Validator::make` ile tanımlamış olduğunuz validator nesnesini `if ($validator->fails()) `şeklinde kontrol etmeden önce aşağıdaki şekilde tanımlama yapmanız yeterli olacaktır.

```php
$validator->after(function($validator) use ($request) {

	$data = array(
		'tcno'          => 'tckimlikno',
		'isim'          => 'XXXXX XXX',
		'soyisim'       => 'XXXXXX',
		'dogumyili'     => 'XXXX',
	);

    if (!TcKimlik::validate($data)) {
        $validator->errors()->add('formfieldname', 'TC Kimlik Numarası vermiş olduğunuz kimlik bilgilerinizle eşleşmiyor');
    }
});
```
