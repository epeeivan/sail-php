<?php

/* 
@description: this file allow you to configure somes aspects of your 
framework like language,database..
*/
setConfig([
    // 
    "base_url"=>"http://127.0.0.1/my_php_framework/",
    "app_name"=>"my_php_framework",
	"language"=>"en",
    "database"=>
    [
        "host"=>"localhost",
        "pass"=>"",
        "dbname"=>"multipresta",
        "user"=>"root",

    ],
    "paths"=>[
        "controllers_folder"=>"app/controllers/",
        "models_folder"=>"app/models/",
        "views_folder"=>"app/views/",
        "libraries_folder"=>"app/libraries/",
        "validations_folder"=>"app/validations/",
        "schemas_folder"=>"app/schemas/",
        "languages_folder"=>"app/language/",
        "storage_folder"=>"app/storage/",
    ],
    "urlLangPrefix"=>["en","fr"],
    "session"=>[
        "driver"=>"database",
        "lifetime"=>120,
    ]
    
]);

