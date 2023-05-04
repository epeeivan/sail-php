<?php

class test extends Controller
{
    public function __construct()
    {

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