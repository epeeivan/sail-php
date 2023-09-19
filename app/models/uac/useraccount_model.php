<?php

namespace app\models\uac;

use system\core\Model;
use app\schemas\uac\useraccount_model_schema;

class useraccount_model extends Model
{
	use useraccount_model_schema;
	public function __construct()
	{
		$this->buildSchema();
	}
	/**
	 * @return [type]
	 */

	public function get(array $columns = null, $request = null)
	{
		$res = $this->db->select(isset($request["count"]) ? "count(*) as count" : "*")
			->from($this->getTable())
			->where("USER_ID", "d","!=");
			// ->and("USER_ID", 2, "=");

		!$this->isColumnEmpty("STATE") ? $res->and("STATE", $this->getColumnValue("STATE")) : null;
		!$this->isColumnEmpty("MARITAL_STATUS") ? $res->and("MARITAL_STATUS", $this->getColumnValue("MARITAL_STATUS")) : null;
		!$this->isColumnEmpty("ADDRESS") ? $res->and("ADDRESS", $this->getColumnValue("ADDRESS")) : null;
		!$this->isColumnEmpty("GENDER") ? $res->and("GENDER", $this->getColumnValue("GENDER")) : null;
		!$this->isColumnEmpty("EMAIL_VERIFIED") ? $res->and("EMAIL_VERIFIED", $this->getColumnValue("EMAIL_VERIFIED")) : null;
		!$this->isColumnEmpty("NATIONALITY") ? $res->and("NATIONALITY", $this->getColumnValue("NATIONALITY")) : null;
		!$this->isColumnEmpty("BIRTHDATE") ? $res->and("BIRTHDATE", $this->getColumnValue("BIRTHDATE")) : null;
		!$this->isColumnEmpty("API_TOKEN") ? $res->and("API_TOKEN", $this->getColumnValue("API_TOKEN")) : null;
		!$this->isColumnEmpty("USER_ID") ? $res->and("USER_ID", $this->getColumnValue("USER_ID")) : null;

		(isset($request["start"]) && isset($request["end"])) ? $res->limit($request["start"], $request["end"]) : null;
		$res->get();
		return isset($request["count"]) ? $res->single()["count"] : (!$this->isColumnEmpty("USER_ID") ? $res->single() : $res->result());
	}
	public function connect()
	{
		return $this->db->select("*")
			->from($this->getTable())
			->where("PASSWORD", $this->getColumnValue("PASSWORD"))
			->and("EMAIL_ADDRESS", $this->getColumnValue("EMAIL_ADDRESS"))
			->get()
			->single();
	}
	public function getFromEmail()
	{
		return $this->db->select("*")
			->from($this->getTable())
			->where("EMAIL_ADDRESS", $this->getColumnValue("EMAIL_ADDRESS"))
			->get()
			->single();
	}
	public function getFromPhoneNumber()
	{
		return $this->db->select("*")
			->from($this->getTable())
			->where("PHONE_NUMBER", $this->getColumnValue("PHONE_NUMBER"))
			->get()
			->single();
	}
}
