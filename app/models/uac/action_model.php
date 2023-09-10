<?php

namespace app\models\uac;

use system\core\Model;
use app\schemas\uac\action_model_schema;

class action_model extends Model
{
	use action_mod	el_schema;
	public function __construct()
	{
		$this->buildSchema();
	}
}
