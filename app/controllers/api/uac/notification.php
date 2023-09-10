<?php

namespace app\controllers\api;

use app\controllers\api\_primaries\primaryApi;

class notification extends primaryApi
{
	protected $notification_model;
	protected $vNotification;
	public function __construct()
	{
		$this->model('notification_model');
		$this->setDb();
		$this->notification_model->setDb($this->getDb());
		$this->validation('vNotification');
	}
	public function get()
	{
		$this->notification_model->hydrater($_GET);
		$this->responseJson($this->notification_model->get());
	}

	public function add()
	{
		$this->genAdd("notification_model", "vNotification");
	}
	public function update()
	{
		$this->genUpdate("notification_model", "vNotification");
	}
	public function delete()
	{
		$this->deleteItems("notification_model", "");
	}
}
