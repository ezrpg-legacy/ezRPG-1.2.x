<?php
/*
  Name: Index.php
  URI: http://ezrpgproject.net/
  Author: Zeggy, Booher, UAKTags
  Package: ezRPG-Core
 */

namespace ezrpg;
use \ezrpg\core\Application,
    \ezrpg\core\ModuleFactory;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define IN_EZRPG as TRUE
define('IN_EZRPG', true);

$rootPath = dirname(__DIR__);

// Traverse back one directory
chdir($rootPath);

// Start the Debug Timer. @since 1.2RC
$debugTimer['ezRPG start'] = microtime(1);

// Check for config and if it has data. @since 1.2RC
if (!file_exists('config.php') OR filesize('config.php') == 0) {
    //Redirect to installer @since 1.x
    header('Location: install/index.php');
    exit(1);
}

session_start();
// Load init.php
require_once $rootPath .'/init.php';


$container = new \Pimple\Container;
$ezrpg = new Application($container);

// Get Config for the game
$ezrpg->getConfigFromCache($rootPath . '/config.php');

// Initialize the Database
$ezrpg->setDatabase();

// Initialize the View Controller;
$ezrpg->initializeView();

// Themes
$ezrpg->getThemes();

// Initialize $player
$ezrpg->container['player'] = 0;

// Initialize hooks
$container['hooks'] = $hooks = $ezrpg->getHooks();

// Run login hooks on player variable
$ezrpg->setPlayer($hooks->run_hooks('player', 0));

if(isset($container['player']->rank) && $container['player']->rank < 5){
	ini_set('display_errors', 0);
}
// Create the Menu object
$ezrpg->container['menu'] = new \ezrpg\core\Menu($container);

$ezrpg->container['menu']->get_menus();

// What's this used for?
$players = new \ezrpg\core\Players($container);

$module_name = $ezrpg->dispatch();
$module_name = $ezrpg->container['hooks']->run_hooks('header', $module_name);
$ezrpg->run($module_name);
//Footer hooks
$ezrpg->container['hooks']->run_hooks('footer', $module_name);
