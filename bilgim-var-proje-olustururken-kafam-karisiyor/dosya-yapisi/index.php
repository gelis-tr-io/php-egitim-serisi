<?php
require 'conf/conf_main.php';

if(isset($_GET['__path'])){


	$current = array_filter(explode('/',$_GET['__path']));
	if(file_exists("templates/".$current[0].".php")){
		require "templates/".$current[0].".php";
	}else{
		require "templates/index.php";
	}
}else{
	require "templates/index.php";
}
