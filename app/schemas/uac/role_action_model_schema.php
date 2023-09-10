<?php

namespace app\schemas\uac;

trait role_action_model_schema
{
	public function buildSchema()
	{
		$this->table('role_action');
		$this->column('ID')->type('bigint(4)')->id();
		$this->column('ROLE_ID')->type('int(11)')->foreign('role');
		$this->column('ACT_ID')->type('bigint(4)')->foreign('action');
		$this->column('CREATED_AT')->type('datetime');
		$this->column('UPDATED_AT')->type('datetime');
		$this->column('DELETED_AT')->type('datetime');
	}
}
