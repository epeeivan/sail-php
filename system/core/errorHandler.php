<?php

namespace system\core;

use Exception;
use system\Loader;

class errorHandler
{
    public function __construct()
    {
    }

    public static function handleError($code)
    {
        if (is_callable($code)) {
            # code...
            try {
                //code...
                $code();
            } catch (\Throwable $th) {
                if (isDebug()) {
                    //throw $th;
                    Loader::load("system/views/errorDisplayer.php", ["error" => $th]);
                    // var_dump($th);                    
                }
            }
        }
    }
}
