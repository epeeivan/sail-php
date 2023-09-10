<?php

namespace app\validations\uac;

use system\core\Validation;

class vUseraccount_role extends Validation
{
	public function __construct()
	{
		$this->setRulesGroup('vUseraccount_role');
		$this->setRules(
			[
				'user_id' => 'required|bigint|max_length(4)',
				'role_id' => 'required|int|max_length(11)',
				// 'created_at' => 'datetime',
				// 'updated_at' => 'datetime',
				// 'deleted_at' => 'datetime',

			]
		);
	}
}
