<?php

namespace app\validations\uac;

use system\core\Validation;

class vNotification extends Validation
{
	public function __construct()
	{
		$this->setRulesGroup('vNotification');
		$this->setRules(
			[
				'notif_type' => 'varchar|max_length(255)',
				'notif_source' => 'bigint|max_length(20)',
				'notif_source_name' => 'varchar|max_length(50)',
				// 'created_at' => 'datetime',
				// 'updated_at' => 'datetime',
				// 'deleted_at' => 'datetime',

			]
		);
	}
}
