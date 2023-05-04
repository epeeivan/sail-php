<?php
use system\core\Controller;
class defaultController extends Controller
{
    public function __construct()
    {

        $this->lang('errors');
        $this->lang('lang');

    }
    public function index(){
        $this->view("home");
    }
    public function test()
    {
        # code...

    }

}