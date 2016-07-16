<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 7/11/2016
 * Time: 8:20 AM
 */
namespace ezRPG\Modules;
use \ezRPG\lib\Base_Module;

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_Upgrades
  Shows a list of Upgrades available.
 */

class Module_Upgrades extends Base_Module
{
    /*
      Function: start
      Displays the members list page.
     */

    public function start()
    {
        //Require login
        requireLogin();


    }

}

?>
