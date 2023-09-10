<?php

namespace app\validations\uac;

use system\core\Validation;

class vCategory extends Validation
{
	public function __construct()
	{
		$this->setRulesGroup('vCategory');
		$this->setRules(
			[
				'user_id' => 'required|bigint|max_length(4)',
				'code' => 'char|max_length(32)',
				'label' => 'varchar|max_length(255)',
				'description' => 'varchar|max_length(2555)',
				// 'created_at' => 'datetime',
				// 'updated_at' => 'datetime',
				// 'deleted_at' => 'datetime',

			]
		);
	}
}
