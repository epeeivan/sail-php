<?php

namespace app\models\uac;

use system\core\Model;
use app\schemas\uac\category_action_model_schema;

class category_action_model extends Model
{
	use category_action_model_schema;
	public function __construct()
	{
		$this->buildSchema();
	}
	public function getCategoryActions($request)
	{
		return $this->extracted($request, "action", "CAT_ID", 0);
	}
	public function getActionCategories($request)
	{
		return $this->extracted($request, "category", "ACT_ID", 1);
	}
	/**
	 * @param mixed $request
	 * @param mixed $res
	 * @param mixed $id
	 * @param mixed $index
	 * 
	 * @return [type]
	 */
	public function extracted($request, $table, $id, $index)
	{
		$res = $this->db->select(isset($request["count"]) ? "count(" . $table . ".*) as count" : $table . ".*")
			->from($this->allTablesString())
			->join($this->foreignColumnLiteral(0), $this->foreignColumnLiteral(0, true))
			->and_join($this->foreignColumnLiteral(1), $this->foreignColumnLiteral(1, true))
			->and($this->foreignColumnLiteral($index), $this->getColumnValue($id));
		$res->order_by($this->foreignColumnLiteral($index), $request["orther"] ?? "desc");
		(isset($request["start"]) && isset($request["end"])) ? $res->limit($request["start"], $request["end"]) : null;

		$res->get();
		return isset($request["count"]) ? $res->single()["count"] : (isset($request["id"]) ? $res->single() : $res->result());
	}
	public function delete()
	{
		return $this->db->delete("from " . $this->getTable())
			->where("CAT_ID", $this->getColumnValue("CAT_ID"))
			->and("ACT_ID", $this->getColumnValue("ACT_ID"))
			->get()
			->execute();
	}
}
