<?php

namespace app\validations\uac;

use system\core\Validation;

class vUseraccount_password extends Validation
{
	public function __construct()
	{
		$this->setRulesGroup('vUseraccount_password');
		$this->setRules(
			[
				'old_password' => 'varchar|max_length(255)',
				'password' => 'varchar|max_length(500)',
				'confirm_password' => 'varchar|max_length(500)|matches[password]',
			]
		);
	}
}
