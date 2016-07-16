<?php
/**
 * Created by PhpStorm.
 * User: tgarrity
 * Date: 7/10/2016
 * Time: 4:39 PM
 */

namespace ezRPG\Modules;

use \ezRPG\lib\Base_Module;

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
        echo $this->container['hooks']->run_hooks('test', "Module: I am saying hello");
    }
}