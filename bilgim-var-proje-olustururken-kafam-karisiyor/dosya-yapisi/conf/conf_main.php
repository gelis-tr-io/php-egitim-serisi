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

$URL = "http://localhost/projeyolu";

// path - proje yolu - dosya işlemleri için kök dizin
$PATH = dirname(__DIR__);





