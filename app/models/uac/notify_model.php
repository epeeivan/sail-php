<?php

namespace app\models\uac;

use system\core\Model;
use app\schemas\uac\notify_model_schema;

class notify_model extends Model
{
	use notify_model_schema;
	public function __construct()
	{
		$this->buildSchema();
	}

	public function getUserNotifications($request = [])
	{
		// return $this->db->select("notification.*")
		$res = $this->db->select(isset($request["count"]) ? "count(*) as count" : "distinct " . "notification.*,notify.STATE")
			->from($this->allTablesString())
			->join($this->foreignColumnLiteral(0), $this->foreignColumnLiteral(0, true))
			->and_join($this->foreignColumnLiteral(1), $this->foreignColumnLiteral(1, true));
		// conditions
		!$this->isColumnEmpty("USER_ID") ? $res->and($this->foreignColumnLiteral(1), $this->getColumnValue("USER_ID")) : null;
		(isset($request["start"]) && isset($request["end"])) ? $res->limit($request["start"], $request["end"]) : null;
		$res->get();
		return isset($request["count"]) ? $res->single()["count"] : (!$this->isColumnEmpty("USER_ID") ? $res->single() : $res->result());
	}
	public function getNotificationUsers()
	{
	}
	public function changeState()
	{
		$this->db->set("STATE", $this->getColumnValue("STATE"));
		$this->db->where("USER_ID", $this->getColumnValue("USER_ID"));
		$this->db->and("NOTIF_ID", $this->getColumnValue("NOTIF_ID"));
		return $this->db->update($this->getTable());
	}
	public function delete()
	{
		return $this->db->delete("from " . $this->getTable())
			->where("USER_ID", $this->getColumnValue("USER_ID"))
			->and("NOTIF_ID", $this->getColumnValue("NOTIF_ID"))
			->get()
			->execute();
	}
}
