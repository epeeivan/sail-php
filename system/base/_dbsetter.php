<?php
namespace system\base;

trait dbsetter
{
	protected $db;
	
	public function setDb($db){
			$this->db=$db;
		}
		public function getDb(){
			 return $this->db;
		}

}

?>