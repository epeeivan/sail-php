<?php

namespace app\controllers\api\uac;

use app\controllers\api\_primaries\primaryApi;

class category extends primaryApi
{
	protected $category_model;
	protected $vCategory;
	public function __construct()
	{
		parent::__construct();
		$this->model('uac/category_model', false, ['schema_path' => 'uac/']);
		$this->setDb();
		$this->category_model->setDb($this->getDb());
		$this->validation('uac/vCategory', false, ['schema_path' => 'uac/']);
	}
	public function get()
	{
		$this->responseJson($this->category_model->get());
	}
	public function getFromUser()
	{
		$this->category_model->hydrater($_GET);
		$this->responseJson($this->category_model->getFromUser($_GET));
	}
	public function add()
	{
		$this->genAdd("category_model", "vCategory");
	}
	public function update()
	{
		$this->genUpdate("category_model", "vCategory");
	}
	public function delete()
	{
		$this->deleteItems("category_model", "");
	}
}
