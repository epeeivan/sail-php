<?php

namespace app\controllers\api\uac;

use app\controllers\api\_primaries\primaryApi;
use app\libraries\mailSender;

class recovery extends primaryApi
{
	protected $recovery_model;
	protected $vRecovery;
	protected $vReset_password;
	// protected $useraccount_model;
	protected $dateFormatter;
	protected $mailSender;

	public function __construct()
	{
		parent::__construct();
		$this->lang("messages");
		// $this->model('uac/useraccount_model', false, ['schema_path' => 'uac/']);
		$this->model('uac/recovery_model', false, ['schema_path' => 'uac/']);
		$this->recovery_model->setDb();
		// $this->useraccount_model->setDb();
		$this->validation('uac/vRecovery', false, ['schema_path' => 'uac/']);
		$this->validation('uac/vReset_password', false, ['schema_path' => 'uac/']);
		$this->library("dateFormatter");
		$this->library("mailSender");
	}
	public function get()
	{
		$this->responseJson($this->recovery_model->get());
	}
	public function add()
	{
		if ($this->vRecovery->run()) {
			$this->recovery_model->hydrater($_POST);
			if ($this->recovery_model->add()) {
				$this->responseJson($_POST);
			} else {
				$this->responseJson();
			}
		} else {
			$this->responseJson();
		}
	}
	public function changePassword()
	{
		if ($this->vReset_password->run()) {
			# code...
			$this->recovery_model->hydrater($_POST);
			$recovery = $this->recovery_model->get();

			if (!empty($recovery)) {
				switch (true) {
					case $recovery["STATE"] == 1:
						$this->responseJson(null, "this recovery has already been validated");
						break;
					case date($recovery["EXPIRE_AT"]) < date("Y-m-d H:i:s"):
						$this->responseJson(null, "this recovery has already expire");
						break;
					default:
						$_POST["state"] = 1;
						$_POST["user_id"] = $recovery["USER_ID"];
						$_POST["password"] = md5($_POST["password"]);
						$_POST["rec_id"] = $recovery["REC_ID"];
						// 
						$this->useraccount_model->hydrater($_POST);
						$this->recovery_model->hydrater($_POST);

						$this->recovery_model->update();
						$this->useraccount_model->update();

						$this->responseJson($this->recovery_model->getColumnValue("REC_ID"), lang("pass_changed"));
						break;
				}
			} else {
				// error
			}
			// $this->responseJson($recovery);
		} else {
			$this->responseJson(null, lang("fields_empty"));
		}
	}
	public function forgotPassword()
	{
		if (isset($_POST["email_address"])) {
			// get email
			$this->useraccount_model->hydrater($_POST);
			$user = $this->useraccount_model->getFromEmail();
			// verify exist
			if (!empty($user)) {
				// exist
				$this->recovery_model->hydrater(["user_id" => $user["USER_ID"]]);
				$unexpiredRecovery = $this->recovery_model->get(null, ["unexpired" => true]);
				// $this->responseJson($unexpiredRecovery);
				if (!empty($unexpiredRecovery)) {
					// exist unexpiredRecovery
					$diff = $this->dateFormatter->diff($unexpiredRecovery["EXPIRE_AT"], date("Y-m-d H:i:s"));
					$this->responseJson(null, lang("retry_after", ["time" => $this->dateFormatter->secToMin($diff)]));
				} else {
					// $date = 
					$expireAt = $this->dateFormatter->strtotime(date("Y-m-d H:i:s") . "+ 5 minutes");
					$recoveryDatas = [
						"rec_token" => getRandomStringUniqid(100),
						"user_id" => $user["USER_ID"],
						"state" => 0,
						"expire_at" => date("Y-m-d H:i:s", $expireAt),
					];

					$this->recovery_model->hydrater($recoveryDatas);
					$this->recovery_model->add();
					// insert code to send mail
					$this->sendMail($user, $recoveryDatas["rec_token"]);
					$this->responseJson([], "");
				}
				// $this->responseJson($user);
			} else {
				$this->responseJson(null, lang("unknow_email"));
			}
		} else {
			$this->responseJson(null, lang("fields_empty"));
		}
	}
	private function sendMail($user, $token)
	{
		$this->mailSender->addAddress($user["EMAIL_ADDRESS"], $user["FIRST_NAME"]);
		$this->mailSender->subject = lang("reset_pass");
		$this->mailSender->AltBody = lang("reset_pass_mess", ["link" => "<a href='" . url("recovery/?token=" . $token) . "' style='color:blue'>reset</a>"]);
		$this->mailSender->send();
	}
	public function update()
	{
		if ($this->vRecovery->run()) {
			$this->recovery_model->hydrater($_POST);
			if ($this->recovery_model->update()) {
				$this->responseJson($_POST);
			} else {
				$this->responseJson();
			}
		} else {
			$this->responseJson();
		}
	}
	public function delete()
	{
		if (isset($_GET['REC_ID'])) {
			$this->recovery_model->hydrater($_GET);
			if ($this->recovery_model->delete()) {
				$this->responseJson($_GET);
			} else {
				$this->responseJson();
			}
		} else {
			$this->responseJson();
		}
	}
}
