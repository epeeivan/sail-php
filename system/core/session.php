<?php

namespace system\core;

use Error;
use PDOException;
use system\base\Database;

/**
 *
 */
class Session
{
    public function __construct()
    {
    }

    /**
     * @return void
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        self::saveSessionData();
    }

    /**
     * @return void
     */
    public static function destroy()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function get($key)
    {
        return $_SESSION[$key];
    }
    /**
     * 
     */
    public static function exist($key = '')
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param $key
     * @return null
     */
    public static function unset($key = null)
    {
        if (isset($key)) {
            unset($_SESSION[$key]);
        } else {
            session_unset();
        }
    }
    /**
     * @return void
     */
    public static function saveSessionData()
    {
        switch (getConfig("session")["driver"]) {
            case "file":
                //                format the session file name
                $sessionFileName = session_id() . ".txt";
                //
                if (!file_exists(sessionStorage($sessionFileName))) {
                    $file = fopen(sessionStorage($sessionFileName), "a+");
                    $data = session_id() . '`' . session_name() . '`' . $_SERVER["REMOTE_ADDR"] . '`' . time() . '`' . time() + ini_get("session.gc_maxlifetime");
                    fwrite($file, $data);
                } else {
                    //
                    $session_data = file_get_contents(sessionStorage($sessionFileName));
                    $session_data_array = explode("`", $session_data);
                    $session_data_array[4] = time() . ini_get("session.gc_maxlifetime");
                    $file = fopen(sessionStorage($sessionFileName), "w+");
                    fwrite($file, implode("`", $session_data_array));
                }
                break;
            case "database":

                $db = new Database();
                $db->query("show tables like 'session'");
                if (!empty($db->single())) {
                    $exist = false;
                    self::setSessionDbData($db);

                    try {
                        $db->add("session");
                    } catch (\PDOException $e) {
                        $exist = true;
                    }
                    if ($exist) {
                        self::setSessionDbData($db, true);

                        $db->where("session_id", session_id());
                        $db->update("session");
                    }
                } else {
                    throw new Error("Session table does'nt exist", 1);
                }

                break;
            case "cookies":
                $s_name = session_name();
                setcookie($s_name(), $_COOKIE[$s_name], time() + ini_get("session.gc_maxlifetime"));
                break;
        }
    }

    /**
     * @param $db
     * @param $update
     * @return void
     */
    private static function setSessionDbData($db, $update = false)
    {
        $db->set("session_id", session_id());
        $db->set("session_name", session_name());
        $db->set("ip_address", $_SERVER["REMOTE_ADDR"]);
        !$update ? $db->set("start", date("Y-m-d H:i:s")) : null;
        $db->set("expire", date("Y-m-d H:i:s", time() + ini_get("session.gc_maxlifetime")));
    }
}
