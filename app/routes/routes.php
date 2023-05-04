<?php
use system\core\Router;


Router::setRoute('default','','defaultController');
Router::setRoute('default/([a-z]+)','','defaultController/${1}');
Router::setRoute('404_error','','errorController');
Router::setRoute('home/comeback/([a-z]+)/$','test/','test/${1}/${2}');
Router::setRoute('home/destroy/([0-9]+)/','test/','test/${1}');
Router::setRoute("ivan",'',function (){
    echo "test";
    Loader::view("home");
}
);