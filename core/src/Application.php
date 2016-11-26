<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 7/10/2016
 * Time: 12:10 AM
 */

namespace ezrpg\core;

use Pimple\Container;


class Application
{

    public $container;

    public $settings;

    public $db;

    public $tpl;

    public $player;

    public $version = "1.2.1.10";

    public $themes;

    public $config;

    public $hooks;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->container['app'] = $this;
    }

    public function getDatabase()
    {
        return $this->db; ///$this->container['db'];
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getTemplateSystem()
    {
        return $this->tpl; //$this->container['tpl'];
    }

    public function setTemplateSystem($tpl)
    {
        $this->tpl = $this->container['tpl'] = $tpl;
    }

    public function getPlayer()
    {
        return $this->player; //$this->container['player'];
    }

    public function setPlayer($args)
    {
        $this->player = $args;
    }

    public function getHooks(){
        // Create a hooks object
        $hooks = new \ezrpg\core\Hooks($this->container);
        $debugTimer['Hooks Initiated:'] = microtime(1);
        // Include all hook files
        $hook_files = scandir(HOOKS_DIR);
        foreach ($hook_files as $hook_file) {
            $path_parts = pathinfo(HOOKS_DIR . '/' . $hook_file);
            if ($path_parts['extension'] == 'php' && $path_parts['basename'] != 'index.php') {
                include_once(HOOKS_DIR . '/' . $hook_file);
            }
        }

        return $this->hooks = $hooks;
    }

    public function getThemes()
    {
        return $this->themes = $this->container['themes'] = new \ezrpg\core\Themes($this->container);
    }

    public function initializePlayer(){
        return $this->player = $this->container['player'] = 0;
    }

    public function setDatabase()
    {
        $this->container['db'] = \ezrpg\core\database\DatabaseFactory::factory($this->container['config']);

        return $this->db = $this->container['db'];
    }

    public function getConfig($path = null){
        if (!array_key_exists('config', $this->container)) {
            if($path == null || strpos($path, '*')) { //logic needs to be tested, here for "load only config files `/XYZ/*`"
                $config_paths = [
                    'core/config/*.php',
                    'modules/*/config.php',
                    'config/*.php',
                ];
                $configLoader = new \ezrpg\core\config\ConfigLoader();
                $config = $configLoader->loadConfigFromPaths($config_paths);
            }else{
                $config = include $path;
            }

            $this->container['config'] = new \ezrpg\core\config\Config($config);
        }
        return $this->container['config'];
    }

    public function getConfigFromCache($path = null){
        if($path == null){
            $this->container['config'] = new \ezrpg\core\config\Config(unserialize(file_get_contents(ROOT_DIR . "/config.php")));
        }else{
            $this->container['config'] = new \ezrpg\core\config\Config(unserialize(file_get_contents($path)));
        }
        return $this->container['config'];
    }

    public function buildConfigCache(){
        if (!array_key_exists('config', $this->container)) {
            $config_paths = [
                'core/config/*.php',
                'modules/*/config.php',
                'config/*.php',
            ];

            $configLoader = new \ezrpg\core\config\ConfigLoader();
            $config = $configLoader->loadConfigFromPaths($config_paths);
            $serialized = serialize($config);
            file_put_contents(ROOT_DIR . "/config.php", $serialized);
        }

    }

    public function initializeView(){
        return new \ezrpg\core\View($this->container);
    }

    public function dispatch(){
        //Set Default module and check if Module is selected in URI
        $default_mod = $this->container['config']['app']['default_module']['value'];

        $module_name = ((isset($_GET['mod']) && ctype_alnum($_GET['mod']) && isModuleActive($_GET['mod']) ) ? $_GET['mod'] : $default_mod);
        $this->container['tpl']->assign('module_name', $module_name);

        //Init Hooks - Runs before Header
        $this->container['hooks']->run_hooks('init');
        return $module_name;
    }

    public function run($module_name, $admin = false){
        //Begin module
        if($admin)
            $module = ModuleFactory::adminFactory($this->container, $module_name);
        else
            $module = ModuleFactory::factory($this->container, $module_name);

        if (isset($_GET['act'])) {
            if (method_exists($module, $_GET['act'])) {
                $reflection = new \ReflectionMethod($module, $_GET['act']);
                if ($reflection->isPublic()) {
                    $module->$_GET['act']();
                } else {
                    $module->start();
                }
            } else {
                $module->start();
            }
        } else {
            $module->start();
        }
    }
}
