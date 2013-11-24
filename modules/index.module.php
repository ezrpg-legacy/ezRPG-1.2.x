<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Module Name: Index
  Description: A basic module for the default landing page. Just shows the index template or the player's home page.
  Author: Zeggy, UAKTags
  Package: nuRPG
  Version: 0.1
 */

class Index extends Base_Module
{
    /*
      Function: start
      Renders  either index.tpl or home.tpl with smarty, depending on if the user is logged in.
     */

    public function start()
    {
        if ( LOGGED_IN )
        {
            $this->loadView('home.tpl', 'Home');
        }
        else
        {	
            $this->loadView('index.tpl', 'Index');
        }
    }

	public function test()
	{
		echo 'test';
	}
}

?>