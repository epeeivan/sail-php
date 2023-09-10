<?php

namespace app\schemas\uac;

trait notification_model_schema
{
	public function buildSchema()
	{
		$this->table('notification');
		$this->column('NOTIF_ID')->type('bigint(4)')->id();
		$this->column('NOTIF_SOURCE')->type('bigint(20)')->id();
		$this->column('NOTIF_SOURCE_NAME')->type('varchar(50)')->id();
		$this->column('NOTIF_TYPE')->type('varchar(255)');
		$this->column('NOTIF_CONTENT')->type('varchar(500)');
		$this->column('CREATED_AT')->type('datetime');
		$this->column('UPDATED_AT')->type('datetime');
		$this->column('DELETED_AT')->type('datetime');
	}
}
