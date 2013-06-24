<?php
// This page cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

// Start Session
session_start();


// Constants
define('CUR_DIR', realpath(dirname(__FILE__)));
define('MOD_DIR', CUR_DIR . '/modules');
define('ADMIN_DIR', CUR_DIR . '/admin');
define('LIB_DIR', CUR_DIR . '/lib');
define('EXT_DIR', LIB_DIR . '/ext');
define('HOOKS_DIR', CUR_DIR . '/hooks');
define('THEME_DIR', CUR_DIR . '/templates/');

require_once CUR_DIR . '/config.php';

// Show errors?
(SHOW_ERRORS == 0) ? error_reporting(0) : error_reporting(E_ALL);

require_once(CUR_DIR . '/lib.php');

// Database
try{
    $db = DbFactory::factory($config_driver, $config_server, $config_username, $config_password, $config_dbname, $config_port);
} catch (DbException $e) {
    $e->__toString();
}

// Database password no longer needed, unset variable
unset($config_password);

// Settings
$settings = new Settings($db);

// Smarty
$tpl = new Smarty();
$tpl->assign('GAMESETTINGS', $settings->setting['general']);
$tpl->addTemplateDir(array(
	'admin' => THEME_DIR . 'themes/admin/',
	'default' => THEME_DIR . 'themes/default/'
));

$tpl->compile_dir  = $tpl->cache_dir = CUR_DIR . '/cache/templates/';

// Themes
$themetpldir = scandir(THEME_DIR . 'themes/', SCANDIR_SORT_NONE);
$moduletpldir = scandir(THEME_DIR . 'modules/', SCANDIR_SORT_NONE);
$entries = array_merge($themetpldir, $moduletpldir);
$templateQuery = $db->execute("SELECT * FROM <ezrpg>themes");
$templateObj = $db->fetchAll($templateQuery);
$templates = array();

foreach ($templateObj as $item => $val){
		$templates[$val->name] = $val->name;
}

foreach ($entries as $entry) {
	if ( $entry != '.' && $entry != '..' && $entry != 'index.php' ){
		if ( !array_key_exists( $entry, $templates) && !array_key_exists( $entry, $tpl->getTemplateDir() ) ) {
			$entry_dir = THEME_DIR . 'themes/' . $entry;
			if (is_dir($entry_dir)) {
				$tpl->addTemplateDir(array(
					$entry => $entry_dir,
				));
				$db->execute("INSERT INTO <ezrpg>themes (name, dir, enabled) VALUES ('".$entry."', '".$entry_dir."', 0)");
			}
			$entry_dir2 = THEME_DIR . 'modules/' . $entry;
			if (is_dir($entry_dir2)) {
				$tpl->addTemplateDir(array(
					$entry => $entry_dir2,
				));
				$db->execute("INSERT INTO <ezrpg>themes (name, dir, enabled, type) VALUES ('".$entry."', '".$entry_dir2."', 0, 1)");
			}
		} else {
			$entry_dir = THEME_DIR . 'themes/' . $entry;
			if (is_dir($entry_dir)) {
				if ( !array_key_exists( $entry, $tpl->getTemplateDir() ) ){
					$tpl->addTemplateDir(array(
						$entry => $entry_dir,
					));
				} elseif(!array_key_exists( $entry, $templates)){
					$db->execute("INSERT INTO <ezrpg>themes (name, dir, enabled) VALUES ('".$entry."', '".$entry_dir."', 0)");
				}
			}
			$entry_dir2 = THEME_DIR . 'modules/' . $entry;
			if (is_dir($entry_dir2)) {
				if ( !array_key_exists( $entry, $tpl->getTemplateDir() ) ){
					$tpl->addTemplateDir(array(
						$entry => $entry_dir2,
					));
				}elseif(!array_key_exists( $entry, $templates)){
					$db->execute("INSERT INTO <ezrpg>themes (name, dir, enabled, type) VALUES ('".$entry."', '".$entry_dir2."', 0, 1)");
				}
			}
		}
	}
}

// Initialize $player
$player = 0;

// Create a hooks object
$hooks = new Hooks($db, $tpl, $player);

// Include all hook files
$hook_files = scandir(HOOKS_DIR);
foreach($hook_files as $hook_file) {
    $path_parts = pathinfo(HOOKS_DIR . '/' . $hook_file);
    if ($path_parts['extension'] == 'php' && $path_parts['basename'] != 'index.php') {
        include_once (HOOKS_DIR . '/' . $hook_file);
	}
}

// Run login hooks on player variable
$player = $hooks->run_hooks('player', 0);

// Create the Menu object
$menu = new Menu($db, $tpl, $player);
$menu->get_menus();