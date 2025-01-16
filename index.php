<?php

if ( !isset($_GET["url"]) || empty($_GET["url"]) ) {
    header("location: ./home");
}

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/public/index.php";