<?php

namespace app\controllers\api;

use app\controllers\api\_primaries\primaryApi;

class action extends primaryApi
{
	protected $action_model;
	protected $vAction;
	public function __construct()
	{
		parent::__construct();
		$this->model('action_model');
		$this->setDb();
		$this->action_model->setDb($this->getDb());
		$this->validation('uac/vAction');
	}
	public function get()
	{
		$this->responseJson($this->action_model->get());
	}
	public function add()
	{
		$this->genAdd("action_model", "vAction");
	}
	public function update()
	{
		$this->genUpdate("action_model", "vAction");
	}
	public function delete()
	{
		$this->deleteItems("action_model", "");
	}
}
