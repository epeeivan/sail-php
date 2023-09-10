<?php

namespace app\schemas\uac;

trait useraccount_role_model_schema
{
	public function buildSchema()
	{
		$this->table('useraccount_role');
		$this->column('ID')->type('bigint(4)')->id();
		$this->column('USER_ID')->type('bigint(4)')->foreign('useraccount');
		$this->column('ROLE_ID')->type('int(11)')->foreign('role');
		$this->column('CREATED_AT')->type('datetime');
		$this->column('UPDATED_AT')->type('datetime');
		$this->column('DELETED_AT')->type('datetime');
	}
}
