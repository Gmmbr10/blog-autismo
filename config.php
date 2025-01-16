<?php

$http = $_SERVER["REQUEST_SCHEME"];
// $path = $_SERVER["HTTP_HOST"] . substr();

$count_uri = - strlen("/".$_GET["url"]);

$folder_default = substr($_SERVER["REQUEST_URI"],0,$count_uri);

$path = $_SERVER["HTTP_HOST"];
$path .= $folder_default;

define("INCLUDE_PATH",$http . "://" . $path );
