<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 7/10/2016
 * Time: 12:10 AM
 */

namespace ezRPG;


class Application
{

    public $hooks;
    public $database;
    public $tpl;
    public $player;

    public function __construct($db, $tpl, $player)
    {
        $this->database = $db; // = $this->getDatabase;
        $this->tpl = $tpl;
        $this->player = $player;
    }

    private function getDatabase()
    {

    }

    private function getTemplateSystem()
    {

    }

    private function getPlayer()
    {

    }

    public function getHooks()
    {
        // Create a hooks object
        $hooks = new \ezRPG\lib\Hooks($this->database, $this->tpl, $this->player);
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
}