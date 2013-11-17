<?php

/*
  Name: Init.php
  URI: http://ezrpgproject.net/
  Author: Zeggy, UAKTags
  Package: ezRPG-Core
 */

// This page cannot be viewed, it must be included
defined('IN_EZRPG') or exit;
// Start Session
session_start();
$app[]='';

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
$app['debugTimer']['Config Loaded:'] = microtime(1);
// Show errors?
(SHOW_ERRORS == 0) ? error_reporting(0) : error_reporting(E_ALL);

require_once(CUR_DIR . '/lib.php');
$app['debugTimer']['Library Loaded:'] = microtime(1);
// Database
try
{
    $app['db'] = DbFactory::factory($config_driver, $config_server, $config_username, $config_password, $config_dbname, $config_port);
}
catch ( DbException $e )
{
    $e->__toString();
}
$app['debugTimer']['DB Loaded:'] = microtime(1);
// Database password no longer needed, unset variable
unset($config_password);

// Settings
$app['settings'] = new Settings($app['db']);
$app['debugTimer']['Settings Loaded:'] = microtime(1);
// Smarty
$app['tpl'] = new Smarty();
$app['debugTimer']['Smarty Loaded:'] = microtime(1);
$app['tpl']->addTemplateDir(array(
    'admin' => THEME_DIR . 'themes/admin/',
    'default' => THEME_DIR . 'themes/default/'
));

$app['tpl']->compile_dir = $app['tpl']->cache_dir = CACHE_DIR . 'templates/';

// Themes
$app['themes'] = new Themes($app['db'], $app['tpl']);
$app['debugTimer']['Themes Initiated:'] = microtime(1);



// Create a hooks object
$app['hooks'] = new Hooks($app);
$app['debugTimer']['Hooks Initiated:'] = microtime(1);
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
$app['debugTimer']['Hooks Loaded :'] = microtime(1);
// Run login hooks on player variable
$app['player'] = $app['hooks']->run_hooks('player', 0);
$app['debugTimer']['Player-Hooks loaded:'] = microtime(1);
// Create the Menu object
$app['menu'] = new Menu($app);
$app['debugTimer']['Menus Initiated:'] = microtime(1);
$app['menu']->get_menus();
$app['debugTimer']['Menus retrieved:'] = microtime(1);
$app['players'] = new Players($app);