<?php

namespace system\core;

abstract class Authorization
{

    protected $authorizations = [];
    public function __construct()
    {
    }
    public function setRole($role)
    {

    }
    public function getRole()
    {
    }
    public static function can($action)
    {
        
        
        if (isset(self::$authorizations[Session::get("user")["label"]]) && isset(self::$authorizations[Session::get("user")["label"]][$action])) {
            return true;
        }
        return false;
    }
}
