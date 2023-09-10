<?php

namespace system\base;

use PDOException;
use system\base\Database;

trait dbsetter
{
	protected $db = null;

	public function setDb(Database $db = null)
	{
		if (is_null($db)) {
			try {
				$this->db = new Database();
			} catch (PDOException $e) {
				echo $e->getCode();
			}
		} else {
			$this->db = $db;
		}
	}
	public function getDb()
	{
		if (is_null($this->db)) {
			# code...
			$this->setDb();
		}
		return $this->db;
	}
}
