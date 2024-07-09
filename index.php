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

$envs  = file_get_contents('./.env');
$envsArr = explode("\n", $envs);

foreach ($envsArr as $env) {
    putenv($env);
}
require "system/loader.php";
Loader::init();

/*
loading the config file
this file define all the parameters off environment
 */

if (is_dir("system/language/" . getConfig("language"))) {
    # code...
    Loader::getFolderContent("system/language/" . getConfig("language"));
}

errorHandler::handleError(function () {
    Router::handle_request(getUrlRoute());
    $session = new session();
    $session->start();
});
