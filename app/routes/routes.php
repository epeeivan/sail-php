<?php
use system\core\Router;
use system\Loader;

Router::setRoute('default','','defaultController');
Router::setRoute('default/([a-z]+)','','defaultController/${1}');
Router::setRoute('404_error','','errorController');
Router::setRoute('home/toto','test/','test');
Router::setRoute('home/bobo/([a-z]+)','test/','test/${1}');
Router::setRoute("ap","","app");
Router::setRoute("ivan",'',function (){
    echo "test";
    Loader::view("home");
}
);