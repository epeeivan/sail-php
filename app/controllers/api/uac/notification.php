<?php

namespace app\controllers\api\uac;

use app\controllers\api\_primaries\primaryApi;

/**
 * [Description notification]
 */
class notification extends primaryApi
{
	protected $notification_model;
	protected $vNotification;
	/**
	 */
	public function __construct()
	{
		$this->model('uac/notification_model', false, ['schema_path' => 'uac/']);
		$this->setDb();
		$this->notification_model->setDb($this->getDb());
		$this->validation('uac/vNotification');
	}
	/**
	 * @return [type]
	 */
	public function get()
	{
		$this->notification_model->hydrater($_GET);
		$this->responseJson($this->notification_model->get());
	}

	/**
	 * @return [type]
	 */
	public function add()
	{
		$this->genAdd("notification_model", "vNotification");
	}
	/**
	 * @return [type]
	 */
	public function update()
	{
		$this->genUpdate("notification_model", "vNotification");
	}
	/**
	 * @return [type]
	 */
	public function delete()
	{
		$this->deleteItems("notification_model", "");
	}
}
