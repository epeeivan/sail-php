<?php

namespace app\controllers\api;

use app\controllers\api\_primaries\primaryApi;
use Dotenv\Parser\Value;
use system\core\Session;

class useraccount_configuration extends primaryApi
{
	protected $useraccount_configuration_model;
	protected $vUseraccount_configuration;
	protected $userRedirect;

	public function __construct()
	{
		$this->model('useraccount_configuration_model', false, ['schema_path' => 'uac/']);
		$this->useraccount_configuration_model->setDb();
		$this->validation('vUseraccount_configuration', false, ['schema_path' => 'uac/']);
		$this->library("userRedirect");
	}
	/**
	 * 
	 */
	public function get()
	{
		$this->responseJson($this->useraccount_configuration_model->get());
	}
	public function add()
	{
		$this->genAdd("useraccount_configuration_model", "vUseraccount_configuration");
	}
	public function update()
	{
		if (isset($_POST["user_id"])) {
			// getting id
			$_POST["user_id"] = intval($_POST["user_id"]);
			// casting to boolean
			foreach ($_POST as $key => $value) {
				if ($value == "true" || $value == "false") {
					$_POST[$key] =($value == "true" ? 2 : 1);
				}
			}
			// fill model object
			$this->useraccount_configuration_model->hydrater($_POST);
			// get an existing configuration
			$config = $this->useraccount_configuration_model->get(null, $_POST);
			// 
			!empty($config) ? $_POST["config_id"] = $config["CONFIG_ID"] : null;

			$this->useraccount_configuration_model->hydrater($_POST);
			if (empty($config)) {
				// 
				$this->genAdd("useraccount_configuration_model");
			} else {
				// 
				$this->genUpdate("useraccount_configuration_model");
				// $this->responseJson($config);
				$config = $this->useraccount_configuration_model->get(null, $_POST);
				if ($this->userRedirect->isConnected()) {
					# code...
					$_SESSION["user"]["config"] = $config;
					$this->responseJson(Session::get("user")["config"]);
				}
			}
		}
	}
	public function delete()
	{
		$this->deleteItems("useraccount_configuration_model", "");
	}
}
