<?php

namespace app\controllers\api\_primaries;

use system\core\Controller;
use system\core\Validation;
use app\models\uac\useraccount_model;
use Exception;

abstract class primaryApi extends Controller
{
    protected $token;
    protected $useraccount_model;
    public function __construct()
    {
        $this->lang("lang");
        $this->lang("errors");
        $this->lang("labels");
        $this->lang("messages");
        $this->model('uac/useraccount_model', false, ['schema_path' => 'uac/']);
        $this->setDb();
        $this->useraccount_model->setDb($this->db);
        $message = "token_success";
        // $this->useraccount_model = new useraccount_model();
        $tokenResponse = $this->verifyToken();
        if (!$tokenResponse['status']) {
            throw new Exception($tokenResponse["message"], 1);
        }
    }
    /**
     * @param null $data
     * @param null $message
     * @param null $url
     * 
     * @return [type]
     */
    public function responseJson($data = null, $message = null, $url = null)
    {
        if (isset($_GET["token"])) {
            # code...
            return parent::responseJson($data, $message, $url);
        } else {
            return parent::responseJson(null, "no token given");
        }
        return 0;
    }

    public function verifyToken()
    {

        $ret = [
            "status" => false,
            "message" => lang("unknow_token")
        ];
        if (isset($_GET["token"]) && !empty($_GET["token"])) {
            # code...
            $datas  = array(
                "api_token" => $_GET["token"],
            );
            $this->useraccount_model->hydrater($datas);
            $uId = $this->useraccount_model->get();
            if (!empty($uId)) {
                # code...
                $ret["status"] = true;
                $ret["message"] = lang("token_success");
            }
            // $this->responseJson(null, lang($message));
        }

        return $ret;
    }
    /**
     * @param mixed $model
     * @param mixed $idName
     * 
     * @return bool
     */
    protected function explodeMapAndAdd($model, $validation, $idName): bool
    {
        $insertState = true;
        $ids = explode(",", $_POST[$idName]);

        foreach ($ids as $id) {
            if (!empty($id)) {
                $_POST[$idName] = $id;
                $this->$model->hydrater($_POST);
                if ($this->$validation->run()) {
                    # code...
                    if (!$this->$model->add()) {
                        $insertState = false;
                    }
                } else {
                }
            }
        }
        return $insertState;
    }
    public function explodeMapAndAddFromTwo($model, $validation, $idName, $sourceId): bool
    {
        $sourceIds = isset($_POST[$sourceId]) ? explode(",", $_POST[$sourceId]) : [];
        $POST_COPY = $_POST;
        foreach ($sourceIds as $sId) {
            $_POST[$sourceId] = $sId;
            $_POST[$idName] = $POST_COPY[$idName];
            // echo "d";
            $res = $this->explodeMapAndAdd($model, $validation, $idName);
        }
        return $res;
    }
    /**
     * @param mixed $state
     * @param null $message
     * 
     * @return [type]
     */
    public function responseJsonFromState($state, $message = null)
    {
        return $state ? $this->responseJson($_POST, $message ?? lang("add_done")) : $this->responseJson(null, lang("fields_empty"));
    }
    /**
     * @param mixed $model
     * @param null $suffix
     * @param null $type
     * 
     * @return [type]
     */
    public function deleteItems($model, $suffix = null, $type = null)
    {
        if (isset($_GET["id"])) {
            $ids = explode(",", $_GET["id"]);
            $deleteMethod  = "";
            foreach ($ids as $id) {
                $this->$model->setId($id);
                $deleteMethod = 'delete' . ucfirst($suffix ?? "");
                if ($this->$model->$deleteMethod() && isset($type)) {
                    $this->deleteFiles($type, $id);
                }
            }
            $this->responseJson([$ids], count($ids) . " " . lang("delete_done"));
        } else {
            $this->responseJson(lang("no_id"));
        }
    }
    /**
     * @param mixed $type
     * @param mixed $id
     * 
     * @return [type]
     */
    public function deleteFiles($type, $id)
    {
        $dirname = "assets/upload/" . $type . "/" . $type . "_" . $id . "/";
        if (is_dir($dirname)) {
            $dir = opendir($dirname);
            while ($file = readdir($dir)) {
                if (($file != '.' && $file != '..') && !is_dir($dirname . '/' . $file)) {
                    unlink($dirname . $file);
                }
            }
            rmdir($dirname);
        }
    }
    /**
     * @param mixed $model
     * @param mixed $validation
     * 
     * @return [type]
     */
    public function genUpdate($model, $validation = null, $message = null, $return_bool = null)
    {
        $this->genUpAdd($model, $validation, "update", $message, $return_bool);
    }
    /**
     * @param mixed $model
     * @param mixed $validation
     * 
     * @return [type]
     */
    public function genAdd($model, $validation = null, $message = null, $return_bool = null)
    {
        $this->genUpAdd($model, $validation, "add", $message, $return_bool);
    }
    /**
     * @param mixed $model
     * @param mixed $validation
     * @param mixed $func
     * 
     * @return [type]
     */
    public function genUpAdd($model, $validation = null, $func, $message = null, $return_bool = null)
    {
        if ((isset($validation) && $this->$validation->run()) || !isset($validation)) {
            $this->$model->hydrater($_POST);
            if ($this->$model->$func()) {
                if (isset($return_bool)) {
                    return true;
                }
                $this->responseJson($_POST, $message ?? "done");
            } else {
                if (isset($return_bool)) {
                    return false;
                }
                $this->responseJson(null, lang("fields_empty"));
            }
        } else {
            if (isset($return_bool)) {
                return false;
            }
            $this->responseJson(null, lang("fields_empty"));
        }
    }
}
