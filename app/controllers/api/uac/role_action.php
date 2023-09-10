<?php

namespace app\controllers\api;

use app\controllers\api\_primaries\primaryApi;

class role_action extends primaryApi
{
	protected $role_action_model;
	protected $vRole_action;
	public function __construct()
	{
		$this->model('role_action_model');
		$this->setDb();
		$this->role_action_model->setDb($this->getDb());
		$this->validation('vRole_action');
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
