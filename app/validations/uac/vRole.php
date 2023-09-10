<?php

namespace app\validations\uac;

use system\core\Validation;

class vRole extends Validation
{
	public function __construct()
	{
		$this->setRulesGroup('vRole');
		$this->setRules(
			[
				'code' => 'varchar|max_length(255)|required',
				'label' => 'varchar|max_length(255)|required',
				'description' => 'mediumtext|max_length(25000)|required',
				// 'created_at' => 'datetime',
				// 'updated_at' => 'datetime',
				// 'deleted_at' => 'date',

			]
		);
	}
}
