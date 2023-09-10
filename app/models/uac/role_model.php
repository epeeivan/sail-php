<?php

namespace app\models\uac;

use system\core\Model;
use app\schemas\uac\role_model_schema;

class role_model extends Model
{
	use role_model_schema;
	public function __construct()
	{
		$this->buildSchema();
	}
	public function update()
	{
		$this->db->set("CODE", $this->getColumnValue("CODE"));
		$this->db->set("LABEL", $this->getColumnValue("LABEL"));
		$this->db->set("DESCRIPTION", $this->getColumnValue("DESCRIPTION"));
		$this->db->where($this->getId(), $this->getIdValue());
		return $this->db->update($this->getTable());
	}
}
