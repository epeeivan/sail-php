<?php
namespace app\controllers;
use system\core\Controller;
class errorController extends Controller
{
    public function __construct()
    {
    }
    public function index(){
        $this->view('error');
    }
    public function test(){
        echo"eee";
    }

}