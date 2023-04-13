<?php
trait dbsetter
{
	private $db;
	
	public function setDb($db){
			$this->db=$db;
		}
		public function getDb(){
			 return $this->db;
		}

}

?>