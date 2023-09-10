<?php

namespace app\schemas\uac;

trait notify_model_schema
{
	public function buildSchema()
	{
		$this->table('notify');
		$this->column('ID')->type('bigint(4)')->id();
		$this->column('NOTIF_ID')->type('bigint(4)')->foreign('notification');
		$this->column('USER_ID')->type('bigint(4)')->foreign('useraccount');
		$this->column('STATE')->type('bigint(1)');
		$this->column('CREATED_AT')->type('datetime');
		$this->column('UPDATED_AT')->type('datetime');
		$this->column('DELETED_AT')->type('datetime');
	}
}
