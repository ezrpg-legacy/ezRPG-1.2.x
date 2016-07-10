<?php

/*
  Name: Init.php
  URI: http://ezrpgproject.net/
  Author: Zeggy, UAKTags
  Package: ezRPG-Core
 */

namespace ezRPG;
use Pimple\Container;

// This page cannot be viewed, it must be included
defined('IN_EZRPG') or exit;
global $debugTimer;
// Start Session
session_start();


// Constants
define('CUR_DIR', realpath(dirname(__FILE__)));
define('MOD_DIR', CUR_DIR . '/modules/');
define('ADMIN_DIR', CUR_DIR . '/admin');
define('LIB_DIR', CUR_DIR . '/lib');
define('EXT_DIR', LIB_DIR . '/ext');
define('HOOKS_DIR', CUR_DIR . '/hooks');
define('THEME_DIR', CUR_DIR . '/templates/');
define('CACHE_DIR', CUR_DIR . '/cache/');

require_once CUR_DIR . '/config.php';
$debugTimer['Config Loaded:'] = microtime(1);
// Show errors?
(SHOW_ERRORS == 0) ? error_reporting(0) : error_reporting(E_ALL);

require_once(CUR_DIR . '/lib.php');
$debugTimer['Library Loaded:'] = microtime(1);

$container = new Container();
$ezrpg = new \ezRPG\Application($container);
// Database
try
{
    $ezrpg->container['db'] = \ezRPG\lib\DbFactory::factory($config_driver, $config_server, $config_username, $config_password, $config_dbname, $config_port);
}
catch ( DbException $e )
{
    $e->__toString();
}
$debugTimer['DB Loaded:'] = microtime(1);
// Database password no longer needed, unset variable
unset($config_password);
// Settings
$ezrpg->container['settings'] = new \ezRPG\lib\Settings($ezrpg->container['db']);
$debugTimer['Settings Loaded:'] = microtime(1);
// Smarty
$tpl = $ezrpg->container['tpl'] = new \Smarty();
$tpl->caching = 0;  
$tpl->assign('GAMESETTINGS', $ezrpg->container['settings']->setting['general']);
if(DEBUG_MODE)
	echo 'GAMESETTINGS Smarty Variable is being deprecated. Use {settings g=\'general\' n=\'Setting_Name\'} for your GameSettings needs.';

$tpl->addTemplateDir(array(
    'admin' => THEME_DIR . 'themes/admin/',
    'default' => THEME_DIR . 'themes/default/'
));
$debugTimer['Smarty Loaded:'] = microtime(1);
$tpl->compile_dir = $tpl->cache_dir = CACHE_DIR . 'templates/';

// Themes
$ezrpg->container['themes'] = $ezrpg->getThemes();

// Initialize $player
$ezrpg->container['player'] = 0;

/*
// Create a hooks object
$hooks = new Hooks($db, $tpl, $player);
$debugTimer['Hooks Initiated:'] = microtime(1);
// Include all hook files
$hook_files = scandir(HOOKS_DIR);
foreach ( $hook_files as $hook_file )
{
    $path_parts = pathinfo(HOOKS_DIR . '/' . $hook_file);
    if ( $path_parts['extension'] == 'php' && $path_parts['basename'] != 'index.php' )
    {
        include_once (HOOKS_DIR . '/' . $hook_file);
    }
}
*/

$hooks = $ezrpg->getHooks();

$debugTimer['Hooks Loaded :'] = microtime(1);
// Run login hooks on player variable
$ezrpg->setPlayer($hooks->run_hooks('player', 0));

$debugTimer['Player-Hooks loaded:'] = microtime(1);
// Create the Menu object
$menu = new \ezRPG\lib\Menu($container);
$debugTimer['Menus Initiated:'] = microtime(1);
$menu->get_menus();
$debugTimer['Menus retrieved:'] = microtime(1);
$players = new \ezRPG\lib\Players($container);