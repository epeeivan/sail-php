<?php

namespace app\controllers\api\uac;

use app\controllers\api\_primaries\primaryApi;
use system\core\Session;

/**
 * [Description useraccount]
 */
class useraccount extends primaryApi
{
	protected $useraccount_model;
	protected $useraccount_role_model;
	protected $belong_model;
	protected $do_model;
	protected $useraccount_configuration_model;
	protected $vUseraccount;
	protected $vUseraccount_role;
	protected $vUseraccount_password;
	protected $vDo;
	protected $vConnect;
	protected $userRedirect;
	protected $vSet_password;
	public function __construct()
	{
		parent::__construct();
		// lang
		$this->lang("messages");
		// libraries
		$this->library("userRedirect");
		// models
		// $this->model('uac/useraccount_model', true, ['schema_path' => 'uac/']);
		$this->model('uac/useraccount_role_model', false, ['schema_path' => 'uac/']);
		// $this->model('uac/belong_model');
		// $this->model('do_model');
		$this->model('uac/useraccount_configuration_model', false, ['schema_path' => 'uac/']);
		// 
		$this->setDb();
		// setting databases to models
		$this->useraccount_model->setDb($this->db);

		$this->useraccount_role_model->setDb($this->db);
		$this->useraccount_configuration_model->setDb($this->db);
		// validations
		$this->validation('uac/vUseraccount');
		$this->validation('uac/vSet_password');
		$this->validation('uac/vUseraccount_role');
		$this->validation('uac/vConnect');
		// $this->validation('uac/vBelong');
		// $this->validation('uac/vDo');
	}
	/**
	 * @return [type]
	 */
	public function get()
	{
		$this->useraccount_model->hydrater($_GET);
		$this->responseJson($this->useraccount_model->get(null, $_GET));
	}
	/**
	 * @return [type]
	 */
	public function add($boolean_return = null)
	{
		if ($this->vUseraccount->run()) {
			$_POST["password"] = md5($_POST["password"]);
			$_POST["api_token"] = getRandomStringUniqid(60);

			$this->useraccount_model->hydrater($_POST);

			if (!$this->emailExist()) {
				# code...
				if (!$this->phoneNumberExist()) {
					# code...
					if ($this->useraccount_model->add()) {
						$_POST["user_id"] = $this->getDb()->lastInsertId();
						return $boolean_return ? ["status" => true] : $this->responseJsonFromState($this->explodeMapAndAdd("useraccount_role_model", "vUseraccount_role", "role_id"));
					} else {
						$this->responseJson(null, "");
					}
				} else {
					return $boolean_return ? ["status" => false, "message" => lang("item_exist", ["item" => lang("phone_number")])] : $this->responseJson(null, lang("item_exist", ["item" => lang("phone_number")]));
				}
			} else {
				// email exist already
				return $boolean_return ? ["status" => false, "message" => lang("email_exist")] : $this->responseJson(null, lang("email_exist"));
			}
		} else {
			return $boolean_return ? ["status" => false, "message" => lang("fields_empty")] :$this->responseJson(null, lang("fields_empty"));
		}
	}
	private function emailExist()
	{
		return empty($this->useraccount_model->getFromEmail()) ? false : true;
	}
	private function phoneNumberExist()
	{
		return empty($this->useraccount_model->getFromPhoneNumber()) ? false : true;
	}
	public function disconnect()
	{
		Session::destroy();
	}


	/**
	 * @return [type]
	 */
	public function update()
	{
		$this->genUpdate("useraccount_model");
	}
	public function changePassword()
	{
		$this->validation("uac/vUseraccount_password");

		if ($this->vUseraccount_password->run()) {
			$this->useraccount_model->hydrater($_POST);
			$this->useraccount_model->hydrater(["user_id" => $_POST["id"]]);
			// var_dump($this->useraccount_model->get(null, $_GET));
			$user = $this->useraccount_model->get(null, $_GET);
			if (!empty($user)) {
				if ($user["PASSWORD"] == md5($_POST["old_password"])) {
					if ($user["PASSWORD"] != md5($_POST["password"])) {
						// update
						$_POST["password"] = md5($_POST["password"]);
						$this->userRedirect->isConnected() ? Session::get("user")["profile"]["PASSWORD"] = $_POST["password"] : '';
						$this->genUpdate("useraccount_model", null, lang("pass_changed"));
						// $this->responseJson(null, lang("pass_changed"));
					} else {
						// the given new password is the old
						$this->responseJson(null, lang("pass_unchanged"));
					}
				} else {
					// error old password doesn't match current
					$this->responseJson(null, lang("old_password_error"));
				}
			} else {
				// error
			}
		} else {
			$this->responseJson(null, lang("fields_empty"));
		}
	}

	/**
	 * @return [type]
	 */
	public function connect()
	{
		if ($this->vConnect->run()) {
			# code...
			$_POST["password"] = md5($_POST["password"]);
			$this->useraccount_model->hydrater($_POST);
			$profile = $this->useraccount_model->connect();
			if (empty($profile)) {
				# code...
				$this->responseJson(null, lang("connection_error"));
			} else {
				// get session informations
				// 
				$urlToRedirect = $this->connectInSession($profile);
				$this->responseJson([], "login done", url($urlToRedirect));
			}
		} else {
			$this->responseJson(null, lang("fields_empty"));
		}
	}
	private function connectInSession($profile = null)
	{
		$this->useraccount_role_model->hydrater(["USER_ID" => $profile["USER_ID"]]);
		// 
		$urlToRedirect = "dashboard/schoolSelect";
		$roles = $this->useraccount_role_model->getUserRoles();
		$config = $this->useraccount_configuration_model->get(null, ["user_id" => $profile["USER_ID"]]);
		// 
		$session_datas = ["profile" => $profile, "roles" => $roles];
		!empty($config) ? $session_datas["config"] = $config : null;
		// 
		// if (!empty($esth) && is_array($esth)) {
		// 	if (count($esth) > 1) {
		// 		# code...
		// 		$session_datas["esth"] = $esth;
		// 	} else {
		// 		$session_datas["current_esth"] = $esth[0];
		// 		$urlToRedirect = "dashbaord";
		// 	}
		// }
		Session::set("user", $session_datas);
		return $urlToRedirect;
	}
	/**
	 * @return [type]
	 */
	public function logout()
	{
		Session::destroy();
		$this->responseJson(null, lang("logout_successfull"), "");
	}
	public function register()
	{
		// $this->responseJson($_POST);
		if ($this->vUseraccount->run()) {
			// 
			$this->useraccount_model->hydrater($_POST);
			// var_dump($this->emailExist());
			// echo $this->useraccount_model->getColumnValue("EMAIL_ADDRESS");
			if (!$this->emailExist()) {
				if (!$this->phoneNumberExist()) {
					# code...
					$_POST["api_token"] = getRandomStringUniqid(60);
					$_POST["password"] = md5($_POST["password"]);
					$_POST["state"] = 1;
					// 
					$this->useraccount_model->hydrater($_POST);
					$this->useraccount_model->add();
					// 
					$userId = $this->getDb()->lastInsertId();
					// 
					$useraccount_role_datas = [
						"role_id" => isset($_POST["teacher"]) ? 2 : 1,
						"user_id" => $userId,
					];

					$this->useraccount_role_model->hydrater($useraccount_role_datas);
					$this->useraccount_role_model->add();
					// 
					if (!isset($_POST["teacher"])) {
						# code...
						if (isset($_POST["et_id"]) && isset($_POST["spec_id"]) && isset($_POST["level"])) {
							# code...
							$belongDatas = [
								"user_id" => $userId,
								"state" => 0,
								"et_id" => $_POST["et_id"]
							];
							$this->belong_model->hydrater($belongDatas);
							$this->belong_model->add();
							// 
							$doDatas = [
								"user_id" => $userId,
								"spec_id" => $_POST["spec_id"],
								"level" => $_POST["level"],
								"do_year" => date("Y")
							];
							$this->do_model->hydrater($doDatas);
							$this->do_model->add();
						}
					}
					// $_POST["USER_ID"] = $userId;
					$this->useraccount_model->hydrater(["user_id" => $userId]);
					$profile = $this->useraccount_model->get(null, ["id" => $userId]);
					// $this->responseJson($profile);
					$urlToRedirect = $this->connectInSession($profile);
					$this->responseJson([], "register done", url($urlToRedirect));

					// $this->;
				} else {
					$this->responseJson(null, lang("item_exist", ["item" => lang("phone_number")]));
				}
			} else {
				$this->responseJson(null, lang("email_exist"));
			}
		} else {
			$this->responseJson(null, lang("fields_empty"));
		}
	}
	public function modifyPassword()
	{
	}
	/**
	 * @return [type]
	 */
	public function delete()
	{
		$this->deleteItems("useraccount_model", "");
	}
}
