<?php

namespace system\core;

use system\Loader;

class Router
{
    /**
     * @var
     */
    private static $buildRoute = null;
    private static $currentRoute = null;
    public function __construct()
    {
        # code...
    }

    /**
     * @return mixed
     */
    public static function getRoutes()
    {
        return $GLOBALS["routes"];
    }

    /**
     * @return null
     */
    public static function getCurrentRoute($key = null)
    {
        return ($key == null | ($key != null && !is_array(self::$currentRoute))) ?
            self::$currentRoute : (
                (is_array(self::$currentRoute) && isset(self::$currentRoute[$key])) ?
                self::$currentRoute[$key] :
                null
            );
    }

    /**
     * @param null $currentRoute
     */
    public static function setCurrentRoute($currentRoute)
    {
        self::$currentRoute = $currentRoute;
    }

    /**
     * @return null
     */
    public static function getBuildRoute()
    {
        return self::$buildRoute;
    }

    /**
     * @param null $buildRoute
     */
    public static function setBuildRoute($buildRoute)
    {
        self::$buildRoute = $buildRoute;
    }

    /**
     * @param $route
     * @return mixed|null
     */
    public static function getRoute($route)
    {
    }

    /**
     * @param $routes
     * @return mixed
     */
    public static function setRoutes($routes)
    {
        foreach ($routes as $key => $route) {
            self::setRoute($route[0], $route[1], $route[0]);
        }
    }

    /**
     * @param $route
     * @param $link
     * @return void
     */
    public static function setRoute($name, $path, $resource)
    {
        $GLOBALS["routes"][$name] = array(
            'path' => $path,
            'resource' => $resource
        );
    }

    /**
     * @param $urlRoute
     * @return array|false
     */
    public static function routeExist($urlRoute)
    {
        //$patterns = explode('`','#'.implode('$#`#',array_keys($GLOBALS['routes'])).'$#');
        //var_dump(implode('/',$GLOBALS['routes']));
        foreach ($GLOBALS['routes'] as $routeName => $routeInfos) {
            $tmp_route = null;

            if (is_callable($routeInfos['resource'])) {
                if (preg_match('#' . $routeName . '#', $urlRoute)) {
                    self::setCurrentRoute($routeInfos);
                    return true;
                }
            } else {
                $tmp_route = preg_replace('#' . $routeName . '#', $routeInfos['resource'], $urlRoute);

                if ($tmp_route != $urlRoute) {
                    self::setBuildRoute($tmp_route);
                    self::setCurrentRoute($routeInfos);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $route
     * @return mixed
     */
    private static function getRouteKey($route)
    {
        return self::getRoute($route)['routeKey'];
    }

    /**
     * @return void
     */
    public static function parseRequest($urlRoute)
    {
        $buildRouteArray = array();
        $path = null;

        if (self::routeExist($urlRoute)) {
            if (is_callable(self::getCurrentRoute('resource'))) {
                return self::getCurrentRoute('resource');
            } else {
                $buildRouteArray = explode('/', self::getBuildRoute());
                $path = self::getCurrentRoute('path') . $buildRouteArray[0];
            }
        } else {
            $buildRouteArray = explode('/', $urlRoute);

            if (!file_exists(getConfig("paths")["controllers_folder"] . $buildRouteArray[0] . ".php")) {
                $buildRouteArray = ["", $buildRouteArray[0]];
            }
        }

        return self::resourceInfo($buildRouteArray, $path);
    }

    /**
     * @param $buildRouteArray
     * @param $path
     * @return array
     */
    public static function resourceInfo($buildRouteArray, $path = null): array
    {
        $resourceInfo = array(
            'path' => '',
            'name' => '',
            'method' => '',
            'params' => []
        );
        $resourceInfo['name'] = $buildRouteArray[0];
        if (isset($buildRouteArray[1])) {
            $resourceInfo['method'] = $buildRouteArray[1];
        }

        if (isset($path)) {
            $resourceInfo['path'] = $path;
        } else {
            $resourceInfo['path'] = $buildRouteArray[0];
        }

        if (count($buildRouteArray) > 2) {
            unset($buildRouteArray[0]);
            unset($buildRouteArray[1]);
            $resourceInfo['params'] = $buildRouteArray;
        }
        return $resourceInfo;
    }


    /**
     * @param $loader
     * @param $controllerAndAction
     * @param $get
     * @return void
     */
    public static function handle_request($urlRoute, $isDefault = false)
    {
        // var_dump(self::routeExist($urlRoute));
        // var_dump($urlRoute);
        // echo "<br/>";
        $resourceInfo = self::parseRequest($urlRoute);
        // var_dump($resourceInfo);
        // echo "<br/>";

        if (!empty($urlRoute)) {
            if (is_callable($resourceInfo)) {
                $resourceInfo();
            } else {
                if (empty($resourceInfo["path"])) {
                    # code...
                    $default = 'default/' . $resourceInfo['method'] . '/' . implode('/', $resourceInfo['params']);
                            self::handle_request($default, true);
                } else {
                    if (true) {

                        $resource = Loader::call($resourceInfo['path']);
                        if (!empty($resourceInfo['method'])) {

                            if (method_exists($resource, $resourceInfo['method'])) {
                                $method = $resourceInfo['method'];
                                $resource->$method(!empty($resourceInfo['params']) ? $resourceInfo['params'] : null);
                            } else {
                                self::handle_request('404_error');
                            }
                        } else {
                            $method = 'index';
                            $resource->$method();
                        }
                    } else {

                        if (!$isDefault) {
                            $default = 'default/' . $resourceInfo['name'] . '/' . implode('/', $resourceInfo['params']);
                            self::handle_request($default, true);
                        } else {
                            self::handle_request('404_error');
                        }
                    }
                }
            }
        } else {
            if (self::routeExist('default')) {
                self::handle_request('default');
            }
        }
    }
}

$Router = new Router();
