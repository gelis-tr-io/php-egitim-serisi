# XSS Açıkları ve XSS Açıklarından Korunmak

### XSS nedir ?
Açılımını soran olursa "Cross Site Scripting" diye geçer. Yeni başlayanların çoğunlukla verdiği açıklardan biridir. Açığın oluşmasının sebebi kullanıcılardan gelen GET POST değerlerini filtrelememiş olmamızdır yani formlardan arama kutularından gelen veriyi filtreden geçirmeden işlememizden kaynaklanır.

###  XSS açığı vermişsek bundan nasıl faydalanırlar.
Evvela cookilerimizi çalabilirler. Kendi websitelerine yönlendirme yapabilirler kısaca javascript ile web sitemize verilebilecek hertürlü zararı verebilirler. 

### XSS açığından nasıl korunurum?
Birçok yöntemi mevcut aslında en başta PHP'de tanımlı olan `strip_tags` fonksiyonundan bahsedeyim hatta direkt örnekle açıklayayım.

```php 
  
  // Aşağıdaki kodu direkt çıktı alırsak sayfa bize bir alert verir. 
  $metin = "<script>alert('XSS Testi');</script>";
  
  // Bu kodun çıktısı  ise string olarak alert('XSS Testi'); şeklindedir.
  $temizle = strip_tags($metin);
  

```

Diyelimki kullanıcıdan table, img gibi etiketler almanız gerek yada kullanıcı bunları kullanabilir bunun için strip_tags fonksiyonunda bunları tanımlamanız yeterli olacaktır.

```php

  // <table> ve <img>'e izin verelim
  strip_tags($metin, '<table><img>');

```

Ama yinede `strip_tags` pek yeterli bir  fonksiyon değil. 2. Yöntem .htaccsess ile  engelleme ama belirteyim html kodlarını engelleyeceği için yine yeterli olmayabilir. 

```
ewriteEngine On
RewriteCond %{QUERY_STRING} base64_encode.*\(.*\) [OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2})
RewriteRule ^(.*)$ index.php [F,L]
```

### En garanti yöntem çok başarılı bir fonksiyon `Clean Fonksiyonu.`

Buna benzer bir fonksiyon yazmıştım ama komşunun tavuğu komşuya kaz görünürmüş misali stackoverflowda gözüme çok daha düzgün gelen ve baya ince düşünülerek hazırlanan bi fonksiyonu görünce kullanmaya başladım yaklaşık 2  yıldır kullanıyorum ve herhangi bir problem çıkarmadı bana.. İstediğim gibi html etiketlerini kullandırabiliyor zararlı kodlardan da arındırabiliyorum kullanıcıdan gelen verimi.

Aynı zamanda SQL Injection açıklarına karşıda belli bir düzeyde önlem alıyor ama tek başına yeterli değil tabiki. XSS içinse 99.9% güvenliği sağlıyor diyebiliriz.

```php

  
function clean($data)
{
// Fix &entity\n;
$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

// Remove any attribute starting with "on" or xmlns
$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

// Remove javascript: and vbscript: protocols
$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

// Only works in IE: <span style="width: expression(alert('Ping!'));"></spa)n>
$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

// Remove namespaced elements (we do not need them)
$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);



do
{
    // Remove really unwanted tags
    $old_data = $data;
    $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
}
while ($old_data !== $data);

// we are done...
return $data;
}

// Kullanımı 

$baslik = clean($_POST['baslik']);


```
### Kısa bir XSS hikayesi
Zamanında bir anonim mesajlaşma platformunda profildeki hakkında kısmına `script` tagleri arasına bir kaç alert ve console çıktısı yazdırmıştım. profilime gittiğimde alertlar seri şekilde çalıştı platformun ciddi bir kitlesi vardı hala da var. Kafamda bunu paraya çevirme fikirleri dolandı durdu sonra dedim adamlar ne emeklerle bu işi yapıyor bende yazılımcıyım en iyisi adamlarla iletişime geçeyim dedim. İnsan gibi adamlarla iletişim kurdum kuru bir teşekkür ettiler. Mailde sadece "Teşekkürler" yazıyordu. Sonra yakaladığım her açıkta iki kez düşündüm. Her yaptığım projede de xss türü açıklara çok dikkat ettim. Bu da böyle bir anımdır.


Makalelerde okuduklarınızı sadece okuyarak öğrenme imkanınız neredeyse sıfır. Okuduklarınızı farklı kombinasyonlarla mutlaka test edin. Faydalı olması dileğiyle...

