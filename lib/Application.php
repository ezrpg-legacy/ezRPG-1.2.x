<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 7/10/2016
 * Time: 12:10 AM
 */

namespace ezRPG\lib;

use Pimple\Container;


class Application
{

    public $container;

    public $settings;

    public $db;

    public $tpl;

    public $player;

    public $version = "1.2.1.9";

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

    public function getHooks()
    {
        // Create a hooks object
        $hooks = new \ezRPG\lib\Hooks($this->container);
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
        return $this->themes = $this->container['themes'] = new \ezRPG\lib\Themes($this->container);
    }

    public function initializePlayer(){
        return $this->player = $this->container['player'] = 0;
    }

    public function setDatabase()
    {
        $this->container['db'] = \ezRPG\lib\DbFactory::factory($this->container['config']);

        return $this->db = $this->container['db'];
    }

    public function getSettings()
    {
        $this->container['settings'] = new \ezRPG\lib\Settings($this->container['db']);

        return $this->settings = $this->container['settings'];
    }

    public function getConfig($filelocation)
    {
        return $this->config = $this->container['config'] = new \ezRPG\lib\Config($filelocation);
    }

    public function initializeView(){
        return new \ezRPG\lib\View($this->container);
    }

    public function dispatch(){
        //Set Default module and check if Module is selected in URI
        $default_mod = $this->container['settings']->setting['general']['default_module']['value'];

        $module_name = ((isset($_GET['mod']) && ctype_alnum($_GET['mod']) && isModuleActive($_GET['mod']) ) ? $_GET['mod'] : $default_mod);
        $this->container['tpl']->assign('module_name', $module_name);

        //Init Hooks - Runs before Header
        $this->container['hooks']->run_hooks('init');
        return $module_name;
    }

    public function run($module_name, $admin = false)
    {
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