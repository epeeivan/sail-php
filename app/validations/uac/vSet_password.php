<?php

namespace app\validations\uac;

use system\core\Validation;

class vSet_password extends Validation
{
	public function __construct()
	{
		$this->setRulesGroup('vSet_password');
		$this->setRules(
			[
				'password' => 'varchar|max_length(500)',
				'confirm_password' => 'varchar|max_length(500)|matches[password]',

			]
		);
	}
}
