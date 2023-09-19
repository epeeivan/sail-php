<?php

namespace app\controllers\api\uac;

use app\controllers\api\_primaries\primaryApi;

class notify extends primaryApi
{
	protected $notify_model;
	protected $vNotify;
	public function __construct()
	{
		$this->model('uac/notify_model', false, ['schema_path' => 'uac/']);
		$this->notify_model->setDb();
		$this->validation('uac/vNotify');
	}
	public function getUserNotifications()
	{
		$this->notify_model->hydrater($_GET);
		$this->responseJson($this->notify_model->getUserNotifications());
	}

	public function add()
	{
		$this->genAdd("notify_model", "vNotify");
	}
	public function changeState()
	{
		$this->notify_model->hydrater($_POST);
		$this->responseJson($this->notify_model->changeState());
	}
	public function delete()
	{
		$this->notify_model->hydrater($_GET);
		$this->responseJson($this->notify_model->delete());
	}
}
