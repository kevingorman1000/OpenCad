<?php

spl_autoload_register("classAutoLoader");
function classAutoLoader($className){
	$url = $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    
    if(strpos($url, "includes") !== false){
        $path = "../classes/";
    }elseif(strpos($url, "functions") !== false){
        $path = "../classes/";
    }elseif(strpos($url, "actions") !== false){
        $path = "../classes/";
    }elseif(strpos($url, "dataManagement") !== false){
        $path = "../../classes/";
    }elseif(strpos($url, "oc-admin") !== false){
        $path = "../classes/";
    } else{
        $path = "classes/";
    }
    
    
	$extensions = ".class.php";

   require $path . $className . $extensions;
}