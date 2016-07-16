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

    public function __construct(Container $container)
    {
        $this->container = $container;
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
        $this->container['tpl']= $tpl;
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
        $hooks = new \ezRPG\lib\Hooks($this->container);
        $debugTimer['Hooks Initiated:'] = microtime(1);
        // Include all hook files
        $hook_files = scandir(HOOKS_DIR);
        foreach ( $hook_files as $hook_file )
        {
            $path_parts = pathinfo(HOOKS_DIR . '/' . $hook_file);
            if ( $path_parts['extension'] == 'php' && $path_parts['basename'] != 'index.php' )
            {
                include_once (HOOKS_DIR . '/' . $hook_file);
            }
        }
        return $hooks;
    }

    public function getThemes()
    {
        $this->container['themes'] = new \ezRPG\lib\Themes($this->container);
        $debugTimer['Themes Initiated:'] = microtime(1);
        return $this->container['themes'];
    }

    public function setDatabase($db)
    {
        $this->container['db'] = $db;
        return $this->container['db'];
    }

    public function getSettings()
    {
        $this->container['settings'] = new \ezRPG\lib\Settings($this->container['db']);
        return $this->container['settings'];
    }
}