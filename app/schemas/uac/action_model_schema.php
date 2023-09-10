<?php

namespace app\schemas\uac;

trait action_model_schema
{
	public function buildSchema()
	{
		$this->table('action');
		$this->column('ACT_ID')->type('bigint(4)')->id();
		$this->column('CODE')->type('varchar(255)');
		$this->column('LABEL')->type('varchar(255)');
		$this->column('DESCRIPTION')->type('varchar(25000)');
		$this->column('CREATED_AT')->type('datetime');
		$this->column('UPDATED_AT')->type('datetime');
		$this->column('DELETED_AT')->type('datetime');
	}
}
