<?php

namespace app\validations\uac;

use system\core\Validation;

class vUseraccount extends Validation
{
	public function __construct()
	{
		$this->setRulesGroup('vUseraccount');
		$this->setRules(
			[
				'first_name' => 'required|varchar|max_length(255)',
				'last_name' => 'required|varchar|max_length(255)',
				// 'gender' => 'smallint|max_length(6)',
				// 'birthdate' => 'date',
				// 'nationality' => 'varchar|max_length(255)',
				// 'address' => 'varchar|max_length(255)',
				'phone_number' => 'required|varchar|max_length(255)',
				'email_address' => 'required|varchar|max_length(255)',
				// 'marital_status' => 'smallint|max_length(6)',
				'password' => 'required|smallint|max_length(500)',
				// 'created_at' => 'datetime',
				// 'updated_at' => 'datetime',
				// 'state' => 'bigint|max_length(4)',
				// 'deleted_at' => 'datetime',
				// 'disabled_at' => 'datetime',
				// 'api_token' => 'varchar|max_length(255)',

			]
		);
	}
}
