<?php

namespace app\validations\uac;

use system\core\Validation;

class vNotify extends Validation
{
	public function __construct()
	{
		$this->setRulesGroup('vNotify');
		$this->setRules(
			[
				'notif_id' => 'required|bigint|max_length(4)',
				'user_id' => 'required|bigint|max_length(4)',
				'state' => 'char|max_length(32)',
				// 'created_at' => 'datetime',
				// 'updated_at' => 'datetime',
				// 'deleted_at' => 'datetime',

			]
		);
	}
}
