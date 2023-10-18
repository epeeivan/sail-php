<?php

namespace app\models\uac;

use system\core\Model;
use app\schemas\uac\recovery_model_schema;

class recovery_model extends Model
{
	use recovery_model_schema;
	private $hasPreviousCond = false;
	private $funcToCall = "";
	public function __construct()
	{
		$this->buildSchema();
	}
	public function get(array $columns = null, $request = null)
	{
		$res = $this->db->select("*")
			->from($this->getTable())
			->where($this->getId(), "d", "!=");

		!$this->isColumnEmpty($this->getId()) ? $res->and($this->getId(), $this->getIdValue()) : null;
		!$this->isColumnEmpty("USER_ID") ? $res->and("USER_ID", $this->getColumnValue("USER_ID")) : null;
		!$this->isColumnEmpty("STATE") ? $res->and("STATE", $this->getColumnValue("STATE")) : null;
		!$this->isColumnEmpty("REC_TOKEN") ? $res->and("REC_TOKEN", $this->getColumnValue("REC_TOKEN")) : null;
		!$this->isColumnEmpty("EXPIRE_AT") ? $res->and("EXPIRE_AT", "%" . $this->getColumnValue("EXPIRE_AT") . "%", " LIKE") : null;
		isset($request["unexpired"]) ? $res->and("EXPIRE_AT", date("Y-m-d H:i:s"), ">") : null;
		isset($request["expired"]) ? $res->and("EXPIRE_AT", date("Y-m-d H:i:s"), "<") : null;

		switch (true) {
			case isset($request["unexpired"]) || isset($request["expired"]) || !$this->isColumnEmpty("REC_TOKEN"):
				return $res->get()->single();

			default:
				return $res->get()->result();
		}
	}
}
