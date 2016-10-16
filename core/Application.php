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

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->container['app'] = $this;
    }

    public function getDatabase()
    {
        return $this->container['db'];
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getTemplateSystem()
    {
        return $this->container['tpl'];
    }

    public function setTemplateSystem($tpl)
    {
        $this->container['tpl'] = $tpl;
    }

    public function getPlayer()
    {
        return $this->container['player'];
    }

    public function setPlayer($args)
    {

    }

    public function getHooks()
    {
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

        return $hooks;
    }

    public function getThemes()
    {
        return $this->container['themes'] = new \ezrpg\core\Themes($this->container);

    }

    public function initializePlayer(){
        return $this->container['player'] = 0;
    }

    public function setDatabase()
    {
        $this->container['db'] = \ezrpg\core\DbFactory::factory($this->container['config']);

        return $this->container['db'];
    }

    public function getSettings()
    {
        $this->container['settings'] = new \ezrpg\core\Settings($this->container['db']);

        return $this->container['settings'];
    }

    public function getConfig($filelocation)
    {
        return $this->container['config'] = new \ezrpg\core\ConfigOld($filelocation);
    }

    public function initializeView(){
        return new \ezrpg\core\View($this->container);
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
                //die($module_name);
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
