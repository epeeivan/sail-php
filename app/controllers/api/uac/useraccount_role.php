<?php

namespace app\controllers\api;

use app\controllers\api\_primaries\primaryApi;

class useraccount_role extends primaryApi
{
	protected $useraccount_role_model;
	protected $vUseraccount_role;
	public function __construct()
	{
		$this->model('useraccount_role_model');
		$this->setDb();
		$this->useraccount_role_model->setDb($this->getDb());
		$this->validation('vUseraccount_role');
	}
	public function get()
	{
		$this->responseJson($this->useraccount_role_model->get());
	}
	public function add()
	{
		$this->responseJsonFromState($this->explodeMapAndAddFromTwo("useraccount_role_model", "vUseraccount_role", "role_id", "user_id"));
	}
	public function getRoleUsers()
	{
		$this->useraccount_role_model->hydrater($_GET);
		$this->responseJson($this->useraccount_role_model->getRoleUsers());
	}
	public function getUserRoles()
	{
		$this->useraccount_role_model->hydrater($_GET);
		$this->responseJson($this->useraccount_role_model->getUserRoles());
	}
	public function removeUserRole()
	{
		$this->useraccount_role_model->hydrater($_GET);
		$this->responseJson($this->useraccount_role_model->removeUserRole());
	}
}
