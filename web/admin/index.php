<?php

namespace ezrpg\admin;

define('IN_EZRPG', true);
define('IN_ADMIN', true);
session_start();

$rootPath = dirname(dirname(__DIR__));

// Traverse back one directory
chdir($rootPath);

// Check for config and if it has data. @since 1.2RC
if (!file_exists('config.php') OR filesize('config.php') == 0) {
    //Redirect to installer @since 1.x
    header('Location: ../install/index.php');
    exit(1);
}

error_reporting(E_ALL);
ini_set('display_errors', 0);


function ezrpg_Autoloader($pClassName)
{
    $class = str_replace("ezRPG\\core\\", "", $pClassName);
    $class = str_replace("\\", "/",$class);
    if (file_exists(CORE_DIR . "/" . $class . ".php")) {
        include(CORE_DIR . "/" . $class . ".php");
    }

}
spl_autoload_register("ezRPG\\admin\\ezrpg_Autoloader");

require_once $rootPath. '/init.php';

$container = new \Pimple\Container;
$ezrpg = new \ezrpg\core\Application($container);

// Get Config for the game
$ezrpg->getConfig(CUR_DIR . '/config.php');

// Initialize the Database
$ezrpg->setDatabase();

// Settings
$ezrpg->getSettings();

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

// Create the Menu object
$ezrpg->container['menu'] = new \ezrpg\core\Menu($container);

$ezrpg->container['menu']->get_menus();

$players = new \ezrpg\core\Players($container);

// Check player exists
if ($container['player'] == '0') {
    header('Location: ../index.php');
    exit;
}

//Require admin rank
if ($container['player']->rank < 5) {
    header('Location: ../index.php');
    exit;
}

$module_name = $ezrpg->dispatch();
$module_name = $ezrpg->container['hooks']->run_hooks('admin_header', $module_name);
$ezrpg->run($module_name, 1);
//Footer hooks
$ezrpg->container['hooks']->run_hooks('admin_footer', $module_name);