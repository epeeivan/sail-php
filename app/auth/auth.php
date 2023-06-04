<?php

use system\core\authorization;

class auth extends authorization{
    public function __construct(){
        parent::__construct();
        self::defineRoles();
    }
    public static function defineRoles(){
       self::$authorizations = [
            "student"=>array(
                'login' => '', 
            ),
            "admin"=>array(
                'login' => '', 
            ),
            "teacher"=>array(
                'login' => '', 
            ),
            "academic_head"=>array(
                'login' => '', 
            ),
        ];
    }
}