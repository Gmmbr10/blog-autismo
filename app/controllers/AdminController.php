<?php

class AdminController
{

  public function __construct()
  {
    
    $uri = str_replace("admin/","",$_GET["url"]);

    if ( $uri == "admin" ) {
      header("location:./admin/login");
      return;
    }
    
		$url = ( isset($uri) && !empty($uri) ) ? explode("/",strtolower($uri)) : header("location: ../home");

    $controller = ucfirst($url[0]) . "Controller";

		if ( !file_exists( __DIR__ . "/admin/" . $controller . ".php") ) {

			$controller = "ErrorController";

		}

		require_once __DIR__ . "/admin/" . $controller . ".php";
		
		$controller = new $controller();

    
		$method = ( isset($url[1]) && !is_numeric($url[1]) && method_exists($controller, $url[1]) ) ? $url[1] : "index";
    
		array_shift($url);
		array_shift($url);
    
		$data = $url;
    
		call_user_func_array([$controller,$method], [$data]);
    
  }

}