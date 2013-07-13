<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

//Requires

//Functions
$func = array(
			'log', 
			'rand', 
			'text', 
			'player', 
			'validate',
			'security');

foreach ($func as $item){
	$filename = LIB_DIR . '/func.' . $item . '.php';
	if (is_readable($filename)) {
		require_once ($filename);
	}
}
			
//Classes			
$clas = array(
			'dbfactory',
			'modulefactory',
			'base_module',
			'hooks',
			'menu',
			'settings',
			'themes'
			);

foreach ($clas as $item){
	$filename = LIB_DIR . '/class.' . $item . '.php';
	if (is_readable($filename)) {
		require_once ($filename);
	}
}
//Exceptions
require_once (LIB_DIR . '/exception.db.php');

//Constants
require_once (LIB_DIR . '/const.errors.php');

//External Libraries
//Smarty
require_once (EXT_DIR . '/smarty/Smarty.class.php');
?>
