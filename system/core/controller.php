<?php

namespace system\core;

use system\Loader;
use system\base\dbsetter;

class Controller
{
    use dbsetter;
    protected $base_model = null;
    public function __construct()
    {
    }

    /**
     * @param $lib
     * @return void
     */
    public function library($lib, $return = false)
    {

        if ($return) {
            return Loader::library($lib);
        } else {
            $libName = Loader::resourceClassName($lib);
            $this->$libName = Loader::library($lib);
        }
    }

    /**
     * @param $view
     * @param $data
     * @return void
     */
    public function view($view, $data = null)
    {
        # code...
        Loader::view($view, $data);
    }

    /**
     * @param $lang
     * @param $data
     * @return void
     */
    public function lang($lang, $data = null)
    {
        # code...
        Loader::lang($lang, $data);
    }

    /**
     * @param $validation
     * @return void
     */
    public function validation($validation, $return = false)
    {
        # code...
        if ($return) {
            return Loader::validation($validation);
        } else {
            $valName = Loader::resourceClassName($validation);
            $this->$valName = Loader::validation($validation);
        }
    }

    /**
     * @param $mod
     * @param $is_base
     * @return void
     */
    public function model($mod, $is_base = false, $options = null)
    {

        $modName = Loader::resourceClassName($mod);
        if (!isset($options["no_schema"])) {
            # code...
            $schemaName =  ($options["schema_path"] ?? "") . $modName . "_schema";
            Loader::schema($schemaName);
        }
        if ($is_base) {
            $this->base_model = Loader::model($mod);
            // $this->base_model->setDb();
        } else {
            $this->$modName = Loader::model($mod);
            // $this->$modName->setDb(new Database());

        }
    }

    /**
     * @param $address
     * @return void
     */
    public function redirect($address)
    {
        # code...
        header("Location:" . url($address));
    }

    public function responseJson($data = null, $message = null, $url = null)
    {
        header('content-type:application/json');
        $response = array(
            'status' => false,
            'data' => [],
        );

        if (!empty($data)) {
            $response['status'] = true;
            $response['data'] = $data;
        }
        isset($url) ?
            $response['url'] = $url : '';
        isset($message) ?
            $response['message'] = $message : '';

        if (!empty(getErrors())) {
            $response["errors"] = getErrors();
        }


        echo $this->safe_json_encode($response);
    }

    private function safe_json_encode($value)
    {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            $encoded = json_encode($value, JSON_PRETTY_PRINT);
        } else {
            $encoded = json_encode($value);
        }
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $encoded;
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_UTF8:
                $clean = $this->utf8ize($value);
                return $this->safe_json_encode($clean);
            default:
                return 'Unknown error'; // or trigger_error() or throw new
                Exception();
        }
    }

    private function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
        } else if (is_string($mixed)) {
            return utf8_encode($mixed);
        }
        return $mixed;
    }
}
