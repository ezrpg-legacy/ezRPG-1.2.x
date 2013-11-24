<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Module Name: Logout
  Description: This module clears the session data to logout the user.
  Author: Zeggy, UAKTags
  Package: nuRPG
  Version: 0.1
 */

class Logout extends Base_Module
{
    /*
      Function: start
      Clears session data and redirects back to homepage.
     */

    public function start()
    {
        unset($_SESSION['hash']);
        unset($_SESSION['userid']);
        session_unset();
        session_destroy();

        $this->app['hooks']->run_hooks('logout');

        $this->setMessage('You have been logged out!');
        header('Location: index.php');
        exit;
    }

}

?>
