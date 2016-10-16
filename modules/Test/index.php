<?php
/**
 * Created by PhpStorm.
 * User: tgarrity
 * Date: 7/10/2016
 * Time: 4:39 PM
 */

namespace ezRPG\Modules;

use \ezrpg\core\Base_Module;

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

class Module_Test extends Base_Module
{
    public function __construct($container, $menu)
    {
        parent::__construct($container, $menu);
    }

    public function start()
    {
        if(isset($_GET['act']))
        {
            $this->test($_GET['act']);
        }
        else{
            die('You need to put in an action to test');
        }
    }

    public function test($what)
    {
        switch($what){
            case "hookoutput":
                echo $this->container['hooks']->run_hooks('test', "Module: I am saying hello");
                break;
            case "exceptionhook":
                $this->container['hooks']->run_hooks('exceptionTest', $_GET['type']);
                break;
            case "register":
                $this->container['hooks']->run_hooks('register_after', "1");
                break;
            default:
                echo 'nothing to test here';
        }
    }
}