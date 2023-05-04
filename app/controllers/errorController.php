<?php
use system\core\Controller;
class errorController extends Controller
{
    public function __construct()
    {
    }
    public function index(){
        $this->view('error');
    }

}