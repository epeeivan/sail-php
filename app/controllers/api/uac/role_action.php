<?php

namespace app\controllers\api\uac;

use app\controllers\api\_primaries\primaryApi;

class role_action extends primaryApi
{
	protected $role_action_model;
	protected $vRole_action;
	public function __construct()
	{
		$this->model('uac/role_action_model', false, ['schema_path' => 'uac/']);
		$this->setDb();
		$this->role_action_model->setDb($this->getDb());
		$this->validation('uac/vRole_action', false, ['schema_path' => 'uac/']);
	}
	// public function get()
	// {
	// 	$this->role_action_model->hydrater($_GET);
	// 	$this->responseJson($this->role_action_model->get());
	// }
	public function add()
	{
		$this->responseJsonFromState($this->explodeMapAndAddFromTwo("role_action_model", "vRole_action", "act_id", "role_id"));
	}
	public function getRoleActions()
	{
		$this->role_action_model->hydrater($_GET);
		$this->responseJson($this->role_action_model->getRoleActions($_GET));
	}
	public function getActionRoles()
	{
		$this->role_action_model->hydrater($_GET);
		$this->responseJson($this->role_action_model->getActionRoles($_GET));
	}
	public function hasAction()
	{
		$this->role_action_model->hydrater($_GET);
		$this->responseJson($this->role_action_model->hasAction($_GET));
	}

	public function delete()
	{
		$this->role_action_model->hydrater($_GET);
		$this->responseJson($this->role_action_model->get());
	}
}
