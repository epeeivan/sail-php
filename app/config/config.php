<?php

/* 
@description: this file allow you to configure somes aspects of your 
framework like language,database..
*/
setConfig([
    // 
    "base_url"=>"",
    "app_name"=>"",
	"language"=>"fr",
    "database"=>
    [
        "host"=>"localhost",
        "pass"=>"",
        "dbname"=>"",
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
    ],
    "urlLangPrefix"=>["en","fr"]
    
]);

