<?php
namespace app\controllers\test;
use system\core\Controller;
class test extends Controller
{
    public function __construct()
    {

    }
    function index(){
        echo "test";
    }
    function monday($params){
        var_dump($params);
        echo 'i am monday';
    }
    function tuesday($params){
        var_dump($params);
        echo 'i am tuesday';
        $this->view('home');
    }
}