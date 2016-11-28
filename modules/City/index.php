<?php

namespace ezrpg\Modules;
use \ezrpg\core\Base_Module;

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_City
  This is a very simple module, designed to simply display a static template page.
 */

class Module_City extends Base_Module
{
    public function __construct($container)
    {
        parent::__construct($container);
    }

    /*
      Function: start
      Displays the city.tpl template. That's all!
     */

    public function start()
    {
        //Require the user to be logged in
        requireLogin();
        $args['begin'] = false;
        $args['endings'] = false;
        $args['showchildren'] = false;
        $this->menu->get_menus("UserMenu", $args);
        $this->menu->get_menus("WorldMenu", $args);
        $this->menu->get_menus("City", $args);
        $this->loadView('city.tpl', 'City');
    }

}

?>
