<?php

class ApiController
{

	public function __construct()
	{

		$uri = str_replace("api/", "", $_GET["url"]);

		$url = (isset($uri) && !empty($uri)) ? explode("/", strtolower($uri)) : header("location: ../home");

		$controller = ucfirst($url[0]) . "Controller";

		if (!file_exists(__DIR__ . "/api/" . $controller . ".php")) {

			$controller = "ErrorController";
		}

		require_once __DIR__ . "/api/" . $controller . ".php";

		require_once __DIR__ . "/../core/Model.php";

		$controller = new $controller();

		$method = (isset($url[1]) && !is_numeric($url[1]) && method_exists($controller, $url[1])) ? $url[1] : "index";

		array_shift($url);
		array_shift($url);

		$data = $url;

		call_user_func_array([$controller, $method], [$data]);
	}
}
