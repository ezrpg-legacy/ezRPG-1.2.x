<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Module Name: Skelton
  Description: This is a skeleton module, which can be used as the starting point for coding new modules.
  Author: Zeggy, UAKTags
  Package: nuRPG
  Version: 0.1
*/
class Module_Skeleton extends Base_Module
{
    /*
      Function: start
      This is the function that is called to display the module to the player.
      This is where most of your player-facing code will go.
      
      Since this module extens Module_Base, you can use the following class variables:
      $dbase - An instance of the database class.
      $tpl - A template smarty object.
      $player - A player result set from the database, or 0 if not logged in.
    */
    public function start()
    {
        // You may call the requireLogin() function if this module is only available to players who are logged in.
        requireLogin();
    }
}
?>