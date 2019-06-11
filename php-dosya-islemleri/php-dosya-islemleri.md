# PHP Dosya İşlemleri
Maalesef bir bir buçuk aylık  bir  ara vermek zorunda  kalmıştım geri döndüm. Aklıma gelen ilk konuda da bir döküman oluşturayım dedim malum hergün ihtiyaç olan bilgiler aklıma gelen fonksiyonlar oldukçada eklerim buraya. Özellikle döküman halinde görmek istediğiniz bir konu varsa lütfen soru başlığı açarak belirtin. 
### Klasör Oluşturma

```php 
 mkdir('deneme-klasor', izinDegeri);
```
izinDegeri = CHMOD değerlerine denktir

### Klasör Silme

```php
  rmdir('deneme-klasor');
```
Klasör sadece boşken silinebilir diğer türlü php hata verecektir bilginize.

### Klasör Taşıma ve İsim Değiştirme

```php
  // 1
 rename('dosya-yolu/dosya','yeni-dosya-yolu/dosya');
 // 2
 rename('dosya-yolu/dosya','dosya-yolu/yeni-dosya');
```
1. Örnekte sadece dosyanın yolunu değiştirdim dosyanın adını aynı bıraktım.
2. Örnekte  ise sadece dosyanın adını değiştirdim.

### PHP Dosya Oluşturma
```php
touch('dosya.txt',degisiklikZamani);
```
degisiklikZamani =  Default olarak dosyayı oluşturduğunuz zamanı alır ama isterseniz şu şekilde bir ayar yapabilirsiniz
```php
$degisiklikZamani = time() + 3600;
touch('dosya.txt,$degisiklikZamani);
```
Şimdi Dosyanın değişiklik zamanı oluşturduğunuz zamandan 1 saat ileride oldu.

### Dosyayı Açma ve Erişim İzinleri
```php
fopen('dosya-yolu/dosya', yetki');
```

### fopen() İçin Yetki Açıklamaları
* ```r```	Dosya sadece okumak için açılır
* ```r+```	Dosya hem okumak hem de yazmak için açılır. 
* ```w```	Dosya sadece yazmak için açılır. Dosya içeriği silinir.
* ```'w+```	Dosya hem okumak hem de yazmak için açılır. Dosya içeriği silinir baştan yazılır.
* ```a```	Dosya sadece yazmak için açılır. Dosya içerği silinmez sonuna eklemeye devam eder.
* ```a+```	Dosya hem okumak hem de yazmak için açılır. Dosya içerği silinmez sonuna eklemeye devam eder.
* ```x```	Dosya oluşturulur ve sadece yazmak için açılır
* ```x+```	Dosya oluşturulur ve hem okumak hem de yazmak için açılır.Dosya zaten mevcutsa fopen() FALSE döndürürek başarısız olur ve E_WARNING seviyesinde bir hata üretir. Dosya mevcut değilse oluşturulmaya çalışılır. Bu işlem, open(2) sistem çağrısı için O_EXCL|O_CREAT seçeneklerini belirtmeye eşdeğerdir.

### Dosya'nın Var olup Olmadığını Kontrol Etme
```php
if(file_exists('dosya-yolu/dosya')){
   echo 'dosya var.';
}else{
  echo 'Dosya Bulunamadı';
}
```
Umarım faydası olur.
