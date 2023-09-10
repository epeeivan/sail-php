<?php

/* 
@description: this file allow you to configure somes aspects of your 
framework like language,database..
*/
setConfig([
    // 
    "base_url"=>"http://127.0.0.1/sail-php/",
    "app_name"=>"sail-php",
	"language"=>"en",
    "database"=>
    [
        "host"=>"localhost",
        "pass"=>"",
        "dbname"=>"uac",
        "user"=>"root",

    ],
    "paths"=>[
        "controllers_folder"=>"app\\controllers\\",
        "models_folder"=>"app\\models\\",
        "views_folder"=>"app\\views\\",
        "libraries_folder"=>"app\\libraries\\",
        "validations_folder"=>"app\\validations\\",
        "schemas_folder"=>"app\\schemas\\",
        "languages_folder"=>"app\\language\\",
        "storage_folder"=>"app\\storage\\",
    ],
    "mode"=>"debug",
    "urlLangPrefix"=>["en","fr"],
    "session"=>[
        "driver"=>"file",
        "lifetime"=>120,
    ]
    
]);

