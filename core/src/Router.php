<?php

namespace ezrpg\core;

defined('IN_EZRPG') or exit;

/*
  Class: Router
  A class to handle the Router system
 */

class Router
{

    protected $container;
    protected $router;

    public function __construct($container)
    {
        $this->container = $container;
        $this->router = $this->container['router'] = new \AltoRouter();
        $this->configureDefaultRoutes();
        $this->generateRoutes();
    }

    private function configureDefaultRoutes(){
        $this->router->setBasePath('');
        $this->router->map('GET','/', 'index.php');
        $this->router->map('GET','/index.php', 'index.php');
        $this->router->map('GET', '/[a:mod]', 'index.php');
        $this->router->map('GET', '/[a:mod]/', 'index.php');
    }

    private function generateRoutes(){
        foreach($this->getAllRoutes() as $key => $route) {

            $this->router->map(
                $route[0]
                , $route[1]
                , "index.php"
                , $key
            );
        }
    }

    private function getAllRoutes(){
        $config_path = '{modules/*/routes.php}';
        $config_files_path = glob($config_path, GLOB_BRACE);
        $config = [];

        foreach ($config_files_path as $config_file_path) {
            $config_data = include $config_file_path;
            $config = $this->mergeArrays($config, $config_data);
        }

        return $config;
    }

    /**
     * Merge arrays recursively.
     *
     * @author Andy <andyidol@gmail.com>
     */
    private function mergeArrays($arr1, $arr2)
    {
        foreach($arr2 as $key => $value) {
            if(array_key_exists($key, $arr1) && is_array($value)) {
                $arr1[$key] = $this->mergeArrays($arr1[$key], $arr2[$key]);
            } else {
                $arr1[$key] = $value;
            }
        }

        return $arr1;
    }
}
