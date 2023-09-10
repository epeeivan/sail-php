<?php

namespace app\models\uac;

use system\core\Model;
use app\schemas\uac\role_action_model_schema;

class role_action_model extends Model
{
	use role_action_model_schema;
	public function __construct()
	{
		$this->buildSchema();
	}
	public function getRoleActions($request)
	{
		return $this->extracted($request, "action", "ROLE_ID", 0);
	}
	public function hasAction($request)
	{
		$res = $this->db->select("*")
			->from($this->allTablesString())
			->join($this->foreignColumnLiteral(0), $this->foreignColumnLiteral(0, true))
			->and_join($this->foreignColumnLiteral(1), $this->foreignColumnLiteral(1, true))
			->and("role.ROLE_ID", $request["role_id"])
			->and("action.CODE", $request["code"])
			->get()
			->single();
		return empty($res) ? false : true;
	}

	public function getActionRoles($request)
	{
		return $this->extracted($request, "role", "ACT_ID", 1);
	}
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
			->where("ROLE_ID", $this->getColumnValue("ROLE_ID"))
			->and("ACT_ID", $this->getColumnValue("ACT_ID"))
			->get()
			->execute();
	}
}
