<?php

namespace app\controllers\api\uac;

use app\controllers\api\_primaries\primaryApi;

/**
 * [Description category_action]
 */
class category_action extends primaryApi
{
	protected $category_action_model;
	protected $vCategory_action;
	public function __construct()
	{
		$this->model('uac/category_action_model', false, ['schema_path' => 'uac/']);
		$this->setDb();
		$this->category_action_model->setDb($this->getDb());
		$this->validation('uac/vCategory_action', false, ['schema_path' => 'uac/']);
	}
	/**
	 * @return [type]
	 */
	public function getCategoryActions()
	{
		$this->category_action_model->hydrater($_GET);
		$this->responseJson($this->category_action_model->getCategoryActions($_GET));
	}
	/**
	 * @return [type]
	 */
	public function getActionCategories()
	{
		$this->category_action_model->hydrater($_GET);
		$this->responseJson($this->category_action_model->getActionCategories($_GET));
	}
	public function add()
	{
		$this->genAdd("category_action_model", "vCategory_action");
	}
	public function delete()
	{
		$this->category_action_model->hydrater($_GET);
		$this->responseJson($this->category_action_model->delete());
	}
}
