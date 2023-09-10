<?php

namespace app\schemas\uac;

trait recovery_model_schema
{
	public function buildSchema()
	{
		$this->table('recovery');
		$this->column('REC_ID')->type('bigint(20)')->id();
		$this->column('REC_TOKEN')->type('varchar(100)');
		$this->column('USER_ID')->type('bigint(20)')->foreign('useraccount');
		$this->column('STATE')->type('int(11)');
		$this->column('CREATED_AT')->type('datetime');
		$this->column('UPDATED_AT')->type('datetime');
		$this->column('DELETED_AT')->type('datetime');
		$this->column('EXPIRED_AT')->type('datetime');
	}
}
