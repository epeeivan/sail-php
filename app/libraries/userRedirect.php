<?php

namespace app\libraries;

use system\core\Session;

class userRedirect
{
    public function __construct()
    {
    }
    public function isConnected()
    {
        return Session::exist("user");
    }
    public function isStudent()
    {
        return $this->identificateUserType(1);
    }
    public function isTeacher()
    {
        return $this->identificateUserType(2);
    }
    public function isAdmin()
    {
        return $this->identificateUserType(4);
    }
    private function identificateUserType($userId)
    {
        if ($this->isConnected()) {

            foreach (Session::get("user")["roles"] as $key => $role) {
                # code...
                if ($role["ROLE_ID"] == $userId) {
                    return true;
                }
            };
        }
        return false;
    }
}
