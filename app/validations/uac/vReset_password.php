<?php

namespace app\validations\uac;

use system\core\Validation;

class vReset_password extends Validation
{
    public function __construct()
    {
        $this->setRulesGroup('vReset_password');
        $this->setRules(
            [
                'rec_token' => 'required|varchar|max_length(500)',
                'password' => 'required|varchar|max_length(500)',
                'confirm_password' => 'required|varchar|max_length(500)|matches[password]',
                // 'created_at' => 'datetime',
                // 'updated_at' => 'datetime',
                // 'deleted_at' => 'datetime',

            ]
        );
    }
}
