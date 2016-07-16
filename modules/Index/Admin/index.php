<?php

namespace ezRPG\Modules\Index\Admin;
use \ezRPG\lib\Base_Module;

defined('IN_EZRPG') or exit;

/*
  Class: Admin_Index
  Home page for the admin panel.
 */

class Admin_Index extends Base_Module
{
    /*
      Function: start
      Displays admin/index.tpl
     */

    public function start()
    {
        $CustomMenuArgs['showchildren'] = false;
        $CustomMenuArgs['customtag'] = 'AdminModules';
        $this->menu->get_menus("AdminMenu", $CustomMenuArgs, false, false, 1);
        $this->loadview('index.tpl');
    }

}

?>
