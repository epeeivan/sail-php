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
		$res = $this->db->get("*")
			->from($this->getTable())
			->where($this->getId(), null, "!=");

		!$this->isColumnEmpty($this->getId()) ? $res->and($this->getId(), $this->getIdValue()) : null;
		!$this->isColumnEmpty("USER_ID") ? $res->and($this->getColumn("USER_ID"), $this->getColumn("USER_ID")) : null;
		!$this->isColumnEmpty("STATE") ? $res->and($this->getColumn("STATE"), $this->getColumn("STATE")) : null;
		!$this->isColumnEmpty("REC_TOKEN") ? $res->and($this->getColumn("REC_TOKEN"), $this->getColumn("REC_TOKEN")) : null;
		!$this->isColumnEmpty("EXPIRE_AT") ? $res->and($this->getColumn("EXPIRE_AT"), "%" . $this->getColumn("EXPIRE_AT") . "%", " LIKE") : null;
		!isset($request["unexpired"]) ? $res->and($this->getColumn("EXPIRE_AT"), date("Y-m-d H:i:s"), ">") : null;
		!isset($request["expired"]) ? $res->and($this->getColumn("EXPIRE_AT"), date("Y-m-d H:i:s"), "<") : null;

		return $res->get()->result();
	}
}
