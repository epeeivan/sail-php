<?php

namespace app\models\uac;

use system\core\Model;
use app\schemas\uac\useraccount_configuration_model_schema;

class useraccount_configuration_model extends Model
{
	use useraccount_configuration_model_schema;
	public function __construct()
	{
		$this->buildSchema();
	}
	public function get(array $columns = null, $request = null)
	{
		$columnssString = '*';
		!is_null($columns) ?
			$columnssString = implode(',', $columns) :
			'';
		$res = $this->db->select(isset($request["count"]) ? "count(*) as count" : $columnssString)
			->from($this->getTable());
		// conditional
		(isset($request["user_id"])) ? $res->where("USER_ID", $request["user_id"]) : null;
		(isset($request["date"])) ? $res->where("CREATED_AT", "%" . $request["date"] . "%", " like ") : null;
		// end

		$res->order_by($this->getId(), 'desc');
		// conditional
		(isset($request["start"]) && isset($request["end"])) ? $res->limit($_GET["start"], $_GET["end"]) : null;
		// end
		$res->get();
		return isset($request["count"]) ? $res->single()["count"] : (!empty($request["user_id"]) ? $res->single() : $res->result());
	}
	public function configuration_exist($request)
	{
		return empty($this->get(null, $request)) ? false : true;
	}
}
