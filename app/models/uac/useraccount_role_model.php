<?php

namespace app\models\uac;

use system\core\Model;
use app\schemas\uac\useraccount_role_model_schema;

class useraccount_role_model extends Model
{
	use useraccount_role_model_schema;
	public function __construct()
	{
		$this->buildSchema();
	}

	public function getRoleUsers()
	{
		return $this->db->select("useraccount.*")
			->from($this->allTablesString())
			->join($this->foreignColumnLiteral(0), $this->foreignColumnLiteral(0, true))
			->and_join($this->foreignColumnLiteral(1), $this->foreignColumnLiteral(1, true))
			->and($this->foreignColumnLiteral(1), $this->getColumnValue("ROLE_ID"))
			->get()
			->result();
	}
	public function getUserRoles()
	{
		return $this->db->select("role.ROLE_ID,role.CODE,role.LABEL,role.ROLE_ID,useraccount_role.CREATED_AT")
			->from($this->allTablesString())
			->join($this->foreignColumnLiteral(0), $this->foreignColumnLiteral(0, true))
			->and_join($this->foreignColumnLiteral(1), $this->foreignColumnLiteral(1, true))
			->and($this->foreignColumnLiteral(0), $this->getColumnValue("USER_ID"))
			->get()
			->result();
	}
	public function removeUserRole(){
		return $this->db->delete("from " . $this->getTable())
		->where("USER_ID", $this->getColumnValue("USER_ID"))
		->and("ROLE_ID", $this->getColumnValue("ROLE_ID"))
		->get()
		->execute();
	}
}
