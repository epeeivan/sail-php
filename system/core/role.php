<?php

namespace system\core;

abstract class Role{    
    public function __construct()
    {
        
    }
    public static function define($role){
        $GLOBALS["authorizations"][$role] = [];
    }
    public static function get(){

    }
}