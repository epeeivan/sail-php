<?php

namespace app\models\uac;

use system\core\Model;
use app\schemas\uac\category_model_schema;

class category_model extends Model
{
	use category_model_schema;
	public function __construct()
	{
		$this->buildSchema();
	}
	public function getFromUser($request)
	{
		$res = $this->db->select(isset($request["count"]) ? "count(category.*) as count" : "category.*")
			->from($this->allTablesString())
			->join($this->foreignColumnLiteral(0), $this->foreignColumnLiteral(0, true))
			->and($this->foreignColumnLiteral(0), $this->getColumnValue("USER_ID"))
			->order_by($this->getId(), "desc");
		(isset($request["start"]) && isset($request["end"])) ? $res->limit($request["start"], $request["end"]) : null;
		$res->get();
		return isset($request["count"]) ? $res->single()["count"] : (isset($request["id"]) ? $res->single() : $res->result());
	}
}
