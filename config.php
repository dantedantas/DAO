<?php 
//Método construtor
spl_autoload_register(function($class_name){

	$dirName = "classes";
	$filename = $dirName . DIRECTORY_SEPARATOR . $class_name . ".php";

	if(file_exists(($filename))){
		require_once($filename);
	}
});

?>