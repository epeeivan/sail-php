<?php

namespace app\validations\uac;

use system\core\Validation;

class vAction extends Validation
{
	public function __construct()
	{
		$this->setRulesGroup('vAction');
		$this->setRules(
			[
				'code' => 'varchar|max_length(255)',
				'label' => 'varchar|max_length(255)',
				'description' => 'varchar|max_length(25000)',
				// 'created_at' => 'datetime',
				// 'updated_at' => 'datetime',
				// 'deleted_at' => 'datetime',

			]
		);
	}
}
