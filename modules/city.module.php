<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Module Name: City
  Description: This is a very simple module, designed to simply display a static template page.
  Author: Zeggy, UAKTags
  Package: nuRPG
  Version: 0.1
  
 */

class City extends Base_Module
{
    /*
      Function: start
      Displays the city.tpl template. That's all!
     */

    public function start()
    {
        //Require the user to be logged in
        requireLogin();
        $args['begin'] = FALSE;
        $args['endings'] = FALSE;
        $args['showchildren'] = FALSE;
        $this->menu->get_menus("UserMenu", $args);
        $this->menu->get_menus("WorldMenu", $args);
        $this->menu->get_menus("City", $args);
        $this->loadView('city.tpl', 'City');
    }

}

?>
