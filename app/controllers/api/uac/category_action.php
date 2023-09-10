<?php

namespace app\controllers\api;

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
		$this->model('category_action_model');
		$this->setDb();
		$this->category_action_model->setDb($this->getDb());
		$this->validation('vCategory_action');
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
