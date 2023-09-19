<?php

namespace app\controllers\api\uac;

use app\controllers\api\_primaries\primaryApi;
use system\core\Controller;
use app\validations\vRole;

/**
 * [Description role]
 */
class role extends primaryApi
{
	protected $role_model;
	protected $vRole;
	public function __construct()
	{
		parent::__construct();
		$this->model('uac/role_model', false, ['schema_path' => 'uac/']);
		$this->setDb();
		$this->role_model->setDb($this->db);
		$this->validation('uac/vRole');
	}
	/**
	 * @return [type]
	 */
	public function get()
	{
		$this->responseJson($this->role_model->get(null, $_GET));
	}
	/**
	 * @return [type]
	 */
	public function add()
	{
		if ($this->vRole->run()) {
			var_dump("ff");
			$this->role_model->hydrater($_POST);
			if ($this->role_model->add()) {
				$this->responseJson($_POST);
			} else {
				$this->responseJson();
			}
		} else {
			$this->responseJson(null, lang("fields_empty"));
		}
	}
	public function update()
	{
		// if ($this->vRole->run()) {
		$this->role_model->hydrater($_POST);
		if ($this->role_model->update()) {
			$this->responseJson($_POST);
		} else {
			$this->responseJson();
		}
	}
	public function delete()
	{
		if (isset($_GET['role_id'])) {
			$this->role_model->hydrater($_GET);
			if ($this->role_model->delete()) {
				$this->responseJson($_GET);
			} else {
				$this->responseJson();
			}
		} else {
			$this->responseJson(null, lang("fields_empty"));
		}
	}
}
