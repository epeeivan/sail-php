<?php
/**
 *
 */

use function PHPSTORM_META\type;

/*
 *
 */
require "system/loader.php";
Loader::init();

/**
    loading the config file
    this file define all the parameters off environment
 */

if (is_dir("system/language/".getConfig("language"))) {
    # code...
    Loader::getFolderContent("system/language/".getConfig("language"));
}
//makeSecure(); 
// echo $_SERVER['REQUEST_URI'];

Router::handle_request(getUrlRoute());

