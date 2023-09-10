<?php

namespace app\validations\uac;

use system\core\Validation;

class vCategory_action extends Validation
{
	public function __construct()
	{
		$this->setRulesGroup('vCategory_action');
		$this->setRules(
			[
				'cat_id' => 'required|int|max_length(11)',
				'act_id' => 'required|bigint|max_length(4)',
				// 'created_at' => 'datetime',
				// 'updated_at' => 'datetime',
				// 'deleted_at' => 'datetime',

			]
		);
	}
}
