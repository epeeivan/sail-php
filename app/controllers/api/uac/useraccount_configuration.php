<?php

namespace app\controllers\api\uac;

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
		$this->model('uac/useraccount_configuration_model', false, ['schema_path' => 'uac/']);
		$this->useraccount_configuration_model->setDb();
		$this->validation('uac/vUseraccount_configuration', false, ['schema_path' => 'uac/']);
		$this->library("userRedirect");
	}
	/**
	 * 
	 */
	public function get()
	{
		$this->responseJson($this->useraccount_configuration_model->get());
	}
	public function configurationExist()
	{
		$this->responseJson($this->useraccount_configuration_model->configuration_exist($_GET));
	}
	public function add()
	{var_dump($this->useraccount_configuration_model->configuration_exist($_GET));
		if (!$this->useraccount_configuration_model->configuration_exist($_GET)) {
			# code...
			$this->genAdd("useraccount_configuration_model", "vUseraccount_configuration");
		}else{
			$this->responseJson(null,lang("configuration_exist"));
		}
	}
	public function update()
	{
		if (isset($_POST["user_id"])) {
			// getting id
			$_POST["user_id"] = intval($_POST["user_id"]);

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
