<?php

/**
 *
 */

use function PHPSTORM_META\type;

use system\core\errorHandler;
use system\Loader;
use system\core\Router;
use system\core\Session;

/*
 *
 */

require "system/loader.php";
Loader::init();

/**
loading the config file
this file define all the parameters off environment
 */

if (is_dir("system/language/" . getConfig("language"))) {
    # code...
    Loader::getFolderContent("system/language/" . getConfig("language"));
}
//makeSecure();
// echo $_SERVER['REQUEST_URI'];
//echo getUrlRoute();
//echo gettype(function(){return "test";});
//echo ini_get("session.gc_maxlifetime");
//echo session_id();
//var_dump($_SESSION);
//echo random_bytes(15);
//session_set_cookie_params();
//session_s
errorHandler::handleError(function () {
    Router::handle_request(getUrlRoute());
    $session = new session();
    $session->start();
});

//var_dump($_SESSION);

//echo getConfig("session")["driver"];
