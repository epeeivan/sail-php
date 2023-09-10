<?php

namespace app\validations\uac;

use system\core\Validation;

class vRecovery extends Validation
{
	public function __construct()
	{
		$this->setRulesGroup('vRecovery');
		$this->setRules(
			[
				'rec_token' => 'required|varchar|max_length(100)',
				'user_id' => 'required|bigint|max_length(20)',
				'state' => 'required|int|max_length(11)',
				// 'created_at' => 'required|datetime',
				// 'updated_at' => 'required|datetime',
				// 'deleted_at' => 'required|datetime',

			]
		);
	}
}
