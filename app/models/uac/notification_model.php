<?php

namespace app\models\uac;

use system\core\Model;
use app\schemas\uac\notification_model_schema;

class notification_model extends Model
{
	use notification_model_schema;
	public function __construct()
	{
		$this->buildSchema();
	}
}
