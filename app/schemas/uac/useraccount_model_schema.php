<?php

namespace app\schemas\uac;

trait useraccount_model_schema
{
	public function buildSchema()
	{
		$this->table('useraccount');
		$this->column('USER_ID')->type('bigint(4)')->id();
		$this->column('FIRST_NAME')->type('varchar(255)');
		$this->column('LAST_NAME')->type('varchar(255)');
		$this->column('GENDER')->type('smallint(6)');
		$this->column('BIRTHDATE')->type('date');
		$this->column('NATIONALITY')->type('varchar(255)');
		$this->column('ADDRESS')->type('varchar(255)');
		$this->column('PHONE_NUMBER')->type('varchar(255)');
		$this->column('EMAIL_ADDRESS')->type('varchar(255)');
		$this->column('MARITAL_STATUS')->type('smallint(6)');
		$this->column('EMAIL_VERIFIED')->type('smallint(1)');
		$this->column('PASSWORD')->type('varchar(500)');
		$this->column('CREATED_AT')->type('datetime');
		$this->column('UPDATED_AT')->type('datetime');
		$this->column('STATE')->type('bigint(4)');
		$this->column('DELETED_AT')->type('datetime');
		$this->column('DISABLED_AT')->type('datetime');
		$this->column('API_TOKEN')->type('varchar(255)');
	}
}
