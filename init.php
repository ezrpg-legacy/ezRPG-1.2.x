<?php

/*
  Name: Init.php
  URI: http://ezrpgproject.net/
  Author: Zeggy, UAKTags
  Package: ezRPG-Core
 */

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
// Database
try
{
    $db = DbFactory::factory($config_driver, $config_server, $config_username, $config_password, $config_dbname, $config_port);
}
catch ( DbException $e )
{
    $e->__toString();
}
$debugTimer['DB Loaded:'] = microtime(1);
// Database password no longer needed, unset variable
unset($config_password);

// Settings
$settings = new Settings($db);
$debugTimer['Settings Loaded:'] = microtime(1);
// Smarty
$tpl = new Smarty();
$tpl->assign('GAMESETTINGS', $settings->setting['general']);
$tpl->addTemplateDir(array(
    'admin' => THEME_DIR . 'themes/admin/',
    'default' => THEME_DIR . 'themes/default/'
));
$debugTimer['Smarty Loaded:'] = microtime(1);
$tpl->compile_dir = $tpl->cache_dir = CACHE_DIR . 'templates/';

// Themes
$themes = new Themes($db, $tpl);
$debugTimer['Themes Initiated:'] = microtime(1);

// Initialize $player
$player = 0;

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
$debugTimer['Hooks Loaded :'] = microtime(1);
// Run login hooks on player variable
$player = $hooks->run_hooks('player', 0);
$debugTimer['Player-Hooks loaded:'] = microtime(1);
// Create the Menu object
$menu = new Menu($db, $tpl, $player);
$debugTimer['Menus Initiated:'] = microtime(1);
$menu->get_menus();
$debugTimer['Menus retrieved:'] = microtime(1);