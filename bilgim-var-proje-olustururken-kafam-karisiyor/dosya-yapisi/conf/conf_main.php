<?php
ob_start();
@session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

try {
    $db = new PDO("mysql:host=localhost; dbname=; charset=utf8", "", "");
}catch (PDOException $e){

    print $e->getMessage();
}

// url yolu server değişkeninden otomatik yorumlanıyor
$URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

// path - proje yolu - dosya işlemleri için kök dizin
$PATH = dirname(__DIR__);





