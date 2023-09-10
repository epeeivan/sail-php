<?php

namespace app\schemas\uac;

trait role_model_schema
{
	public function buildSchema()
	{
		$this->table('role');
		$this->column('ROLE_ID')->type('int(11)')->id();
		$this->column('CODE')->type('varchar(255)');
		$this->column('LABEL')->type('varchar(255)');
		$this->column('DESCRIPTION')->type('mediumtext(25000)');
		$this->column('CREATED_AT')->type('datetime');
		$this->column('UPDATED_AT')->type('datetime');
		$this->column('DELETED_AT')->type('date');
	}
}
