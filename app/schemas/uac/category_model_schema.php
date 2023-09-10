<?php

namespace app\schemas\uac;

trait category_model_schema
{
	public function buildSchema()
	{
		$this->table('category');
		$this->column('CAT_ID')->type('int(11)')->id();
		$this->column('USER_ID')->type('bigint(4)')->foreign('useraccount');
		$this->column('CODE')->type('char(32)');
		$this->column('LABEL')->type('varchar(255)');
		$this->column('DESCRIPTION')->type('varchar(2555)');
		$this->column('CREATED_AT')->type('datetime');
		$this->column('UPDATED_AT')->type('datetime');
		$this->column('DELETED_AT')->type('datetime');
	}
}
