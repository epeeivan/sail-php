<?php

/**
 *
 */

namespace system;

use Exception;

class Loader
{
    private static $base_folder = "system/base";
    private static $core_folder = "system/core";

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return void
     */
    public static function init()
    {
        # code...
        self::getFolderContent(self::$base_folder);

        self::getFolderContent(self::$core_folder);
        self::load("app/config/config.php");
        self::load("app/routes/routes.php");
        self::loadPrimary();
    }

    /**
     * @param $controller
     * @return bool
     */
    public static function loadResource($controller): bool
    {

        // $m_link = $this->models_folder . $controller . ".php";
        $link = getConfig('paths')['controllers_folder'] . $controller . ".php";

        // $this->load($m_link);
        // var_dump($link);
        return  self::load($link);

        # code...
    }

    /**
     * @param $lib
     * @return void
     */
    public static function library($lib)
    {
        # code...
        return self::loadAndInitialize(getConfig('paths')['libraries_folder'] . $lib);
    }

    /**
     * @param $mod
     * @return void
     */
    public static function model($mod)
    {
        # code...
        return self::loadAndInitialize(getConfig('paths')['models_folder'] . $mod);
    }
    public static function schema($schema): bool
    {
        # code...
        return self::load(getConfig('paths')['schemas_folder'] . $schema . ".php");
    }

    /**
     * @param $val
     * @return void
     */
    public static function validation($val)
    {
        # code...
        return self::loadAndInitialize(getConfig('paths')['validations_folder'] . $val);
    }

    /**
     * @param $view
     * @param $data
     * @return void
     */

    public static function view($view, $data = null)
    {
        
        #code
        self::load(getConfig('paths')['views_folder'] . $view . ".php", $data);
    }

    /**
     * @param $languageFile
     * @param $data
     * @return void
     */
    public static function lang($languageFile, $data = null)
    {
        #code
        //        var_dump($_COOKIE["mpf_lang"]);
        self::load(getConfig('paths')['languages_folder'] . ($_COOKIE["mpf_lang"] ?? getConfig('language')) . "/" . $languageFile . ".php", $data);
    }

    /**
     * @param $link
     * @param $data
     * @return mixed|null
     */
    public static function loadAndInitialize($link, $data = null)
    {
        if (self::load($link . '.php', $data = null)) {
            $resource = Loader::resourceClassName($link);
            return new (str_replace('/', '\\', $link));
        } else {
            return null;
        }
    }
    /**
     * @param $link
     * @param $data
     * @return bool
     */
    public static function load($link, $data = null)
    {
        # code...

        //code...
        if (!file_exists($link) /*|| !file_exists($m_link)*/) {
            # code...
            throw new Exception("Error Processing Request", 1);

            return false;
        } else {
            // $this->load($m_link);
            if (!is_null($data)) {
                # code...
                foreach ($data as $key => $value) {
                    # code...
                    $$key = $value;
                }
            }
            require $link;
            return true;
        }
    }

    /**
     * @param $controller
     * @return mixed
     */
    public static function call($controller)
    {
        return self::loadAndInitialize(getConfig('paths')['controllers_folder'] . $controller);
    }

    public static function resourceClassName($resourceLink)
    {
        return explode("/", $resourceLink)[count(explode("/", $resourceLink)) - 1];
    }
    /**
     * @param $ressource
     * @param $method
     * @return bool
     */
    public static function ressource_has_method($ressource, $method)
    {
        return method_exists($ressource, $method);
    }

    /**
     * @param $folder
     * @param array|null $exclude
     * @return void
     */
    public static function getFolderContent($folder, array $exclude = null, array $include = null)
    {
        # code...
        $dirname = $folder;
        $dir = opendir($dirname);
        while ($file = readdir($dir)) {
            if (($file != '.' && $file != '..') && !is_dir($dirname . '/' . $file)) {

                if (isset($include)) {

                    foreach ($include as $key => $value) {

                        $res = explode($value, $file);
                        if (count($res) > 1) {
                            require $dirname . '/' . $file;
                        }
                    }
                } else {
                    if ($exclude != null) {
                        # code...
                        // searching for exclude file
                        foreach ($exclude as $key => $value) {
                            # code...
                            if (strpos($file, $value) !== false) {
                                # code...
                                require '' . $dirname . '/' . $file . '';
                            }
                        }
                    } else {
                        require '' . $dirname . '/' . $file . '';
                    }
                }
            } else {
                if ($file != '.' && $file != '..' &&  is_dir($dirname . '/' . $file)) {
                    //echo $dirname .'/'. $file;
                    self::getFolderContent($dirname . '/' . $file, $exclude, $include);
                }
            }
        }

        closedir($dir);
    }
    private static function loadPrimary()
    {
        self::getFolderContent(getConfig('paths')['controllers_folder'], [], ['primary']);
    }
}
