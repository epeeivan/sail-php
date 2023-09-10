<?php

namespace app\schemas\uac;

trait useraccount_configuration_model_schema
{
	public function buildSchema()
	{
		$this->table('useraccount_configuration');
		$this->column('CONFIG_ID')->type('bigint(20)')->id();
		$this->column('USER_ID')->type('bigint(20)')->foreign('useraccount');
		$this->column('ONLINE_PRESENCE')->type('int(11)');
		$this->column('PROFILE_IMAGE')->type('int(11)');
		$this->column('CONSULT_PROFILE')->type('int(11)');
		$this->column('LANGUAGE')->type('varchar(5)');
		$this->column('THEME')->type('varchar(10)');
		$this->column('CREATED_AT')->type('datetime');
		$this->column('UPDATED_AT')->type('datetime');
		$this->column('DELETED_AT')->type('datetime');
	}
}
