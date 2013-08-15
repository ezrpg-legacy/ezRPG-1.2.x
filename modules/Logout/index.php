<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_Logout
  This module clears the session data to logout the user.
 */

class Module_Logout extends Base_Module
{
    /*
      Function: start
      Clears session data and redirects back to homepage.
     */

    public function __construct(&$db, &$tpl, &$player = 0)
    {
        unset($_SESSION['hash']);
        unset($_SESSION['userid']);
        session_unset();
        session_destroy();

        global $hooks;
        $hooks->run_hooks('logout');

        $this->setMessage('You have been logged out!');
        header('Location: index.php');
        exit;
    }

}

?>
