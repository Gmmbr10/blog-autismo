<?php

class LogoutController {

    public function __construct()
    {
        
        session_start();
        session_destroy();

        header("location:" . INCLUDE_PATH . "/home");
        return;
        
    }
    
}