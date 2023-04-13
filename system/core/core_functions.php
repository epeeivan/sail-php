<?php

session_start();
/**
 * @param $key
 * @return mixed|string
 */
function lang($key = null,$params = null){
    if (!is_null($key) && is_string($key) && isset($GLOBALS["lang"][$key])) {
        # code...
        if (isset($params)){
            $ret = $GLOBALS["lang"][$key];
            foreach ($params as $key=>$value){
                $ret = str_replace("{".$key."}",$value,$ret);
            }
            return $ret;
        }else{
            return $GLOBALS["lang"][$key];

        }

    }
    return "";
}

/**
 * @param $errors
 * @return void
 */
function setErrors($errors){
    $GLOBALS["errors"] = $errors;
}

/**
 * @param $label
 * @return string
 */
function getErrors($label){
    $ret = "";
    if (isset($GLOBALS["errors"][$label])) {
        if (is_array($GLOBALS["errors"][$label]) && !empty(is_array($GLOBALS["errors"][$label]))) {
            foreach ($GLOBALS["errors"][$label] as $key => $error) {
                $ret.=$error." ";
            }
        }
    }
    return $ret;
}

/**
 * @param $key
 * @param $value
 * @return void
 */
function setLang($key,$value){
    $GLOBALS["lang"][$key] = $value;
}

/**
 * @param string $url
 * @return string
 */
function url(string $url=""): string
{
    return (isset($_SERVER["REQUEST_SCHEME"])?$GLOBALS["config"]["base_url"]:"http://".$_SERVER["HTTP_HOST"]."/".app_name()."/").$url;
}

function app_name($url=""): string
{
    return $GLOBALS["config"]["app_name"].$url;
}

function setConfig($config){
    $GLOBALS["config"] = $config;
}
function getLanguage(){
    return $_COOKIE["mpf_lang"]??getConfig("language");
}
function setLanguage($language){
//    if
    //var_dump(getConfig('paths')['languages_folder']);
    if(is_dir(getConfig('paths')['languages_folder']."/".$language)){
        setcookie("mpf_lang",$language,time() + (86400 * 30), "/");

//        $configFile = fopen('app/config/config.php','a+');
//        $configFileString = '';
//        $counter = 0;
//        while(!feof($configFile)){
//            $ligne = fgets($configFile);
//
//            $counter++;
//            if(preg_match('+"language"+',$ligne)){
//                $configFileString.="\t\"language\"=>\"".$language."\",\n";
//            }else{
//                $configFileString.=$ligne;
//            }
//        }
//        fclose($configFile);
//        fwrite(fopen('app/config/config.php','w+'),$configFileString);
    }else{
        echo lang('unset_language_dir');
    }

}

function getConfig($item){
    return $GLOBALS['config'][$item]??[];
}

/**
 * @param $fields
 * @return mixed|string
 */
function dbInfo($fields = null){
    if (isset($GLOBALS["config"]["database"][$fields])) {
        //
        return $GLOBALS["config"]["database"][$fields];
    }
    return "";
}



/**
 * @return mixed|string
 */
function makeSecure(){
    if ($_SERVER['REQUEST_SCHEME']!="https"){
        header("Location:".url());
    }
}
function getUrlRoute(){
    //
    $browsed =
    $urlRoute = isset($_SERVER['REQUEST_SCHEME'])?(explode(url(),($_SERVER['REQUEST_SCHEME']??"").'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])):explode("/".app_name()."/",$_SERVER['REQUEST_URI']."/");
    //
//    echo $_SERVER['REQUEST_URI']."/";
//    echo $urlRoute[1]."222";
    if (count($urlRoute)>1){
        //
        $url = explode('?',$urlRoute[1])[0];

        $eventuallyLang = explode('/',$url)[0];
        //chek if the url contain a language preffix
        if (in_array($eventuallyLang,getConfig('urlLangPrefix'))) {
            //remove the
//            echo $eventuallyLang;

            $url = substr($url,(strlen($eventuallyLang)+1));
//            if (getConfig('language')!=$eventuallyLang) {
//                # code...
//                setLanguage($eventuallyLang);
//                header('Location:'.url($urlRoute[1]));
//            }
        }
//        echo $url;
        return $url;
    }

    return "";
}
