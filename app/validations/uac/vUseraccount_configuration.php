<?php

namespace app\validations\uac;

use system\core\Validation;

class vUseraccount_configuration extends Validation
{
	public function __construct()
	{
		$this->setRulesGroup('vUseraccount_configuration');
		$this->setRules(
			[
				'user_id' => 'required|bigint|max_length(20)',
				// 'online_presence' => 'required|int|max_length(11)',
				// 'profile_image' => 'required|int|max_length(11)',
				// 'consult_profile' => 'required|int|max_length(11)',
				// 'language' => 'required|varchar|max_length(5)',
				// 'theme' => 'required|varchar|max_length(10)',

			]
		);
	}
}
