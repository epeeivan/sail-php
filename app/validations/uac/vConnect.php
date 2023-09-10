<?php
namespace app\validations\uac;
use system\core\Validation;

class vConnect extends Validation{
    public function __construct()
    {
        $this->setRulesGroup("vConnect");
        $this->setRules([
            "password"=>"required",
            "email_address"=>"required"
        ]);
        
    }
}