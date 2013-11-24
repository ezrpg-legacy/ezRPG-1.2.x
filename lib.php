<?php

/*
  Name: Lib.php
  URI: http://ezrpgproject.net/
  Author: UAKTags
  Package: ezRPG-Core
 */

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

//Functions
$func = array(
    'log',
    'rand',
    'text',
    'player',
    'validate',
    'security',
	'modules');

foreach ( $func as $item )
{
    $filename = LIB_DIR . '/func.' . $item . '.php';
    if ( is_readable($filename) )
    {
        require_once ($filename);
    }
}

//Classes	

//Exceptions
require_once (LIB_DIR . '/exception.db.php');

//Constants
require_once (LIB_DIR . '/const.errors.php');

//External Libraries
//Smarty
require_once (EXT_DIR . '/smarty/Smarty.class.php');

spl_autoload_extensions('.php, .module.php, .hook.php');

/*** class Loader ***/
function moduleLoader($class)
{
	$filename = strtolower($class) . '.module.php';
	$file = MOD_DIR . '/' . $filename;
	if (!file_exists($file))
	{
		$deprecated = 'modules/'. $class .'/Index.php';
		if(!file_exists($deprecated)){
			return false;
		}else{
			$_SESSION['status_messages']['Admin_Message'] = array('WARN' => 'Module is using deprecating format. Check Changelog for nuRPG');
			include $deprecated;
		}
		$file = 'modules/index.module.php';
		include $file;
	}else{
		include $file;
	}
}

function libraryLoader($class)
{
	$filename = 'class.' . strtolower($class) . '.php';
	$file = LIB_DIR . '/' . $filename;
	if (!file_exists($file))
	{
		$_SESSION['status_messages']['Admin_Message'] = array('WARN' => 'Lib '.$file.' not found');
	}else{
		include_once $file;
	}
}

function adminLoader($class)
{
	$filename = strtolower($class) . '.module.php';
	$file = $filename;
	
	if (!file_exists($file))
	{
		
		$deprecated = $class .'/Index.php';
		if(!file_exists($deprecated)){
			return false;
		}else{
			$_SESSION['status_messages']['Admin_Message'] = array('WARN' => 'Module is using deprecating format. Check Changelog for nuRPG');
			include_once $deprecated;
		}
	}else{
		include_once $file;
	}
}
/*** register the loader functions ***/
	if(!defined('IN_ADMIN'))
		spl_autoload_register('moduleLoader');
	if(defined('IN_ADMIN'))
		spl_autoload_register('adminLoader');
	spl_autoload_register('libraryLoader');
	

?>
